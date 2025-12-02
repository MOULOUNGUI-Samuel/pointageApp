<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Services\PeriodeItemChecker;

class ComplianceBoard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $traceId;

    // Filtres
    public string $search = '';
    public string $filterStatus = 'all'; // all|no_submission|soumis|approuvé|rejeté
    public string $filterPeriode = 'all'; // all|active|expired|no_period
    public ?string $filterCategorie = null;
    public ?string $filterDomaine = null; // NOUVEAU : Filtre par domaine

    // Stats
    public array $stats = [];
    public array $domaineStats = []; // NOUVEAU : Stats par domaine
    public array $statsGlobales = []; // NOUVEAU : Stats globales

    // Modals state
    public ?string $selectedItemForSubmit = null;
    public ?string $selectedSubmissionForSubmit = null;
    public ?string $selectedSubmissionForReview = null;
    public ?string $selectedItemForHistory = null;
    public ?string $selectedItemForPeriode = null;

    /**
     * Calcule l'état de conformité d'un item pour debugging
     */
    private function calculateConformiteStatus(Item $item): array
    {
        $lastSub = $item->lastSubmission;
        $hasActivePeriode = $item->hasActivePeriode;

        if ($lastSub) {
            // Règle métier : Si période active ET statut approuvé → NON CONFORME
            if ($hasActivePeriode && $lastSub->status === 'approuvé') {
                return [
                    'status' => 'non_conforme',
                    'label' => 'Non conforme',
                    'color' => 'rouge',
                    'reason' => 'Nouvelle période active, soumission approuvée obsolète',
                ];
            }

            return [
                'status' => $lastSub->status,
                'label' => match ($lastSub->status) {
                    'approuvé' => 'Approuvé',
                    'rejeté' => 'Rejeté',
                    'soumis' => 'En attente',
                    default => 'Inconnu',
                },
                'color' => match ($lastSub->status) {
                    'approuvé' => 'vert',
                    'rejeté' => 'rouge',
                    'soumis' => 'jaune',
                    default => 'gris',
                },
                'reason' => 'Statut de la dernière soumission',
            ];
        }

        // Pas de soumission
        if ($hasActivePeriode) {
            return [
                'status' => 'non_conforme',
                'label' => 'Non conforme',
                'color' => 'rouge',
                'reason' => 'Période active sans soumission',
            ];
        }

        return [
            'status' => 'aucune',
            'label' => 'Aucune soumission',
            'color' => 'gris',
            'reason' => 'Pas de période active, pas de soumission',
        ];
    }

    private function logReqMeta(string $phase): void
    {
        if (! app()->environment('local')) return;

        // Log::debug("[ComplianceBoard] req meta ({$phase})", [
        //     'trace_id'   => $this->traceId,
        //     'full_url'   => request()->fullUrl(),
        //     'uri'        => request()->path(),
        //     'referer'    => request()->headers->get('referer'),
        //     'user_agent' => request()->userAgent(),
        //     'x-livewire' => request()->headers->get('x-livewire'),
        //     'hx-request' => request()->headers->get('hx-request'),
        //     'turbo'      => request()->headers->get('turbo-visit'),
        // ]);
    }

    public function mount(): void
    {
        $this->traceId = (string) Str::uuid();

        // Log::info('[ComplianceBoard] mount()', [
        //     'trace_id'      => $this->traceId,
        //     'entreprise_id' => session('entreprise_id') ?: null,
        // ]);

        $this->logReqMeta('mount');

        $this->loadStats();
        $this->loadDomaineStats(); // NOUVEAU : Charger les stats par domaine
    }

    public function loadStats(): void
    {
        $today = now()->toDateString();

        $itemIds = DB::table('entreprise_items')
            ->where('entreprise_id', session('entreprise_id'))
            ->where('statut', '1')
            ->pluck('item_id');

        $baseSub = ConformitySubmission::whereIn('item_id', $itemIds)
            ->where('entreprise_id', session('entreprise_id'));

        $this->stats = [
            'total_items'         => $itemIds->count(),
            'avec_periode_active' => PeriodeItem::whereIn('item_id', $itemIds)
                ->where('entreprise_id', session('entreprise_id'))
                ->where('statut', '1')
                ->whereDate('debut_periode', '<=', $today)
                ->whereDate('fin_periode', '>=', $today)
                ->distinct('item_id')->count('item_id'),
            'en_attente'          => (clone $baseSub)->where('status', 'soumis')->count(),
            'approuves'           => (clone $baseSub)->where('status', 'approuvé')->count(),
            'rejetes'             => (clone $baseSub)->where('status', 'rejeté')->count(),
        ];
    }

    /**
     * NOUVEAU : Charger les statistiques par domaine
     */
    public function loadDomaineStats(): void
    {
        $entrepriseId = session('entreprise_id');

    // Récupérer les domaines de l'entreprise
        /** @var \Illuminate\Support\Collection<string, object> $domaines */
        $domaines = DB::table('entreprise_domaines')
            ->where('entreprise_id', $entrepriseId)
            ->where('entreprise_domaines.statut', '1')
            ->join('domaines', 'domaines.id', '=', 'entreprise_domaines.domaine_id')
            ->select('domaines.id', 'domaines.nom_domaine')
            ->orderBy('domaines.nom_domaine')
            ->get();

        $this->domaineStats = [];

        $globalValides       = 0;
        $globalNonConformes  = 0;
        /** @var object{ id:string, nom_domaine:string, icone:?string } $domaine */
        foreach ($domaines as $domaine) {

            // Récupérer les items de ce domaine pour cette entreprise
            $itemIds = DB::table('entreprise_items')
                ->join('items', 'items.id', '=', 'entreprise_items.item_id')
                ->join('categorie_domaines', 'categorie_domaines.id', '=', 'items.categorie_domaine_id')
                ->where('entreprise_items.entreprise_id', $entrepriseId)
                ->where('entreprise_items.statut', '1')
                ->where('categorie_domaines.domaine_id', $domaine->id)
                ->pluck('items.id');

            if ($itemIds->isEmpty()) {
                continue; // Ne pas afficher les domaines sans items
            }

            $totalItems = $itemIds->count();

            // Calculer les stats par periode_state, items valides ET items non conformes
            $items = Item::whereIn('id', $itemIds)->get();
            $periodeStats = [
                'active' => 0,
                'upcoming' => 0,
                'expired' => 0,
                'disabled' => 0,
                'none' => 0,
            ];
            $valides = 0;
            $nonConformes = 0;

            foreach ($items as $item) {
                // 1. Calculer periode_state
                $state = $item->periode_state; // Utilise l'accessor
                if (isset($periodeStats[$state])) {
                    $periodeStats[$state]++;
                }

                // 2. Récupérer la dernière période avec statut = 1 pour cet item
                $activePeriode = PeriodeItem::where('item_id', $item->id)
                    ->where('entreprise_id', $entrepriseId)
                    ->orderByDesc('debut_periode')
                    ->first();

                // 3. Récupérer la dernière soumission
                $lastSub = $item->lastSubmission()->where('entreprise_id', $entrepriseId)->first();

                // 4. Calculer si l'item est VALIDE
                // Valide = soumission approuvée pendant la période active (statut=1)
                if ($lastSub && $lastSub->status === 'approuvé') {
                    $submittedAt = \Carbon\Carbon::parse($lastSub->submitted_at);
                    $debutPeriode = \Carbon\Carbon::parse($activePeriode->debut_periode);

                    // Vérifier que la soumission a été faite pendant ou après le début de la période active
                    if ($submittedAt->greaterThanOrEqualTo($debutPeriode)) {
                        $valides++; // Item valide : soumission correspond à la période active
                    } else {
                        // Soumission approuvée mais pour une ancienne période
                        if (PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId)) {
                            $nonConformes++; // Nouvelle période active = non conforme
                        }
                    }
                } else {
                    // 5. Calculer si l'item est NON CONFORME
                    $hasActivePeriode = PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId);

                    if ($hasActivePeriode) {
                        if (!$lastSub) {
                            // Non conforme : période active sans soumission
                            $nonConformes++;
                        } elseif ($lastSub->status === 'rejeté') {
                            // Non conforme : soumission rejetée
                            $nonConformes++;
                        }
                    }
                }
            }

            $this->domaineStats[] = [
                'id'          => $domaine->id,
                'nom'         => $domaine->nom_domaine,
                // 'icone'       => $domaine->icone ?? 'ti-folder',
                'total'       => $totalItems,
                'valides'     => $valides,
                'non_conformes' => $nonConformes, // Ajout du nombre de non conformes
                'periode_stats' => $periodeStats,
            ];
            // 👉 On cumule ici
            $globalValides      += $valides;
            $globalNonConformes += $nonConformes;
        }
        // Après la boucle, tu stockes ça où tu veux :
        $this->statsGlobales = [
            'valides'       => $globalValides,
            'non_conformes' => $globalNonConformes,
        ];
    }

    /**
     * NOUVEAU : Sélectionner un domaine (filtre)
     */
    public function selectDomaine(?string $domaineId): void
    {
        $this->filterDomaine = $domaineId;
        $this->resetPage();

        Log::info('[ComplianceBoard] Domaine sélectionné', [
            'trace_id' => $this->traceId,
            'domaine_id' => $domaineId,
        ]);
    }

    // Quand la recherche change
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    // Quand le statut de soumission change
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    // Quand la période change
    public function updatingFilterPeriode(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    // Quand la catégorie change
    public function updatingFilterCategorie(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    // NOUVEAU : Quand le domaine change
    public function updatingFilterDomaine(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    /** Ouvrir modal de soumission (création OU modification) */
    public function openSubmitModal(string $itemId, ?string $submissionId = null): void
    {
        $this->selectedItemForSubmit      = $itemId;
        $this->selectedSubmissionForSubmit = $submissionId;

        Log::info('[ComplianceBoard] openSubmitModal()', [
            'trace_id'      => $this->traceId,
            'item_id'       => $itemId,
            'submission_id' => $submissionId,
        ]);

        $this->dispatch('open-submit-modal', itemId: $itemId, submissionId: $submissionId);
    }

    /** Ouvrir modal de révision */
    public function openReviewModal(string $submissionId): void
    {
        $this->selectedSubmissionForReview = $submissionId;

        Log::info('[ComplianceBoard] openReviewModal()', [
            'trace_id'      => $this->traceId,
            'submission_id' => $submissionId,
        ]);

        $this->dispatch('open-review-modal', submissionId: $submissionId);
    }

    /** Ouvrir modal d'historique */
    public function openHistoryModal(string $itemId): void
    {
        $this->selectedItemForHistory = $itemId;
        $this->dispatch('open-history-modal', itemId: $itemId);
    }

    /** Ouvrir modal d'periode */
    public function openPeriodeModal(string $itemId): void
    {
        $this->selectedItemForPeriode = $itemId;
        $this->dispatch('modal-periode-manager', itemId: $itemId);
    }

    #[On('settings-submitted')]
    #[On('settings-reviewed')]
    #[On('wizard-config-reload')]
    #[On('settings-submitted2')]
    #[On('settings-reviewed2')]
    #[On('periodes-updated')]
    public function refresh(): void
    {
        Log::info('[ComplianceBoard] refresh() called', [
            'trace_id' => $this->traceId,
            'event'    => 'Reloading stats and resetting page',
        ]);

        $this->loadStats();
        $this->loadDomaineStats(); // NOUVEAU : Recharger les stats des domaines
        $this->resetPage();

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $this->logReqMeta('render');

        $today   = now()->toDateString();
        $itemIds = DB::table('entreprise_items')
            ->where('entreprise_id', session('entreprise_id'))
            ->where('statut', '1')
            ->pluck('item_id');

        $query = Item::query()
            ->whereIn('id', $itemIds)
            ->with([
                'CategorieDomaine:id,nom_categorie,domaine_id',
                'CategorieDomaine.Domaine:id,nom_domaine', // AJOUT : Charger le domaine
                'periodes' => fn($q) => $q->where('entreprise_id', session('entreprise_id')),
            ])
            ->withCount([
                'submissions as pending_count' => fn($q) =>
                $q->where('entreprise_id', session('entreprise_id'))->where('status', 'soumis'),
            ])
            ->with([
                'lastSubmission' => fn($q) =>
                $q->where('entreprise_id', session('entreprise_id'))->latest('submitted_at')
            ]);

        if ($this->search !== '') {
            $s = trim($this->search);
            $query->where(fn($q) => $q->where('nom_item', 'like', "%{$s}%")
                ->orWhere('description', 'like', "%{$s}%"));
        }

        // NOUVEAU : Filtre par domaine
        if ($this->filterDomaine) {
            $query->whereHas(
                'CategorieDomaine',
                fn($q) =>
                $q->where('domaine_id', $this->filterDomaine)
            );
        }

        if ($this->filterCategorie) {
            $query->where('categorie_domaine_id', $this->filterCategorie);
        }

        if ($this->filterPeriode !== 'all') {
            if ($this->filterPeriode === 'active') {
                $query->whereHas(
                    'periodeActive',
                    fn($q) =>
                    $q->where('entreprise_id', session('entreprise_id'))
                        ->where('statut', '1')
                        ->whereDate('debut_periode', '<=', $today)
                        ->whereDate('fin_periode', '>=', $today)
                );
            } elseif ($this->filterPeriode === 'expired') {
                $query->whereHas('periodes', fn($q) => $q
                    ->where('entreprise_id', session('entreprise_id'))
                    ->where('statut', '1')
                    ->whereDate('fin_periode', '<', $today))
                    ->whereDoesntHave('periodeActive', fn($q) => $q
                        ->where('entreprise_id', session('entreprise_id'))
                        ->where('statut', '1')
                        ->whereDate('debut_periode', '<=', $today)
                        ->whereDate('fin_periode', '>=', $today));
            } elseif ($this->filterPeriode === 'no_period') {
                $query->whereDoesntHave(
                    'periodes',
                    fn($q) =>
                    $q->where('entreprise_id', session('entreprise_id'))->where('statut', '1')
                );
            }
        }

        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'no_submission') {
                $query->whereDoesntHave('submissions', fn($q) => $q->where('entreprise_id', session('entreprise_id')));
            } else {
                $query->whereHas(
                    'lastSubmission',
                    fn($q) =>
                    $q->where('conformity_submissions.status', $this->filterStatus)
                );
            }
        }

        $items = $query->orderBy('nom_item')->paginate(12);
        $entrepriseId = session('entreprise_id');

        // Pour chaque item de la page, on calcule sa période active et son état de conformité
        foreach ($items as $item) {
            // 1) vérifier s'il y a une période de validité active (statut = 1)
            $item->hasActivePeriode = PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId);

            // 2) récupérer la période active (si besoin des dates)
            $item->activePeriode = PeriodeItemChecker::getActivePeriod($item->id, $entrepriseId);

            // 3) calculer l'état de conformité pour debugging (optionnel, uniquement en local)
            if (app()->environment('local')) {
                $item->debugConformiteStatus = $this->calculateConformiteStatus($item);
            }
        }

        $categories = DB::table('entreprise_categorie_domaines')
            ->where('entreprise_categorie_domaines.entreprise_id', $entrepriseId)
            ->where('entreprise_categorie_domaines.statut', '1')
            ->join(
                'categorie_domaines',
                'categorie_domaines.id',
                '=',
                'entreprise_categorie_domaines.categorie_domaine_id'
            )
            ->orderBy('categorie_domaines.nom_categorie')
            ->get([
                'categorie_domaines.id',
                'categorie_domaines.nom_categorie',
            ]);



        return view('livewire.settings.compliance-board', compact('items', 'categories'));
    }
}
