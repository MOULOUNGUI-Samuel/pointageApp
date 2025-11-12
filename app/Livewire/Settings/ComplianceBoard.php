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

    // Stats
    public array $stats = [];

    // Modals state
    public ?string $selectedItemForSubmit = null;
    public ?string $selectedSubmissionForSubmit = null; // <-- AJOUTÉ
    public ?string $selectedSubmissionForReview = null;
    public ?string $selectedItemForHistory = null;

    private function logReqMeta(string $phase): void
    {
        if (! app()->environment('local')) return;

        Log::debug("[ComplianceBoard] req meta ({$phase})", [
            'trace_id'   => $this->traceId,
            'full_url'   => request()->fullUrl(),
            'uri'        => request()->path(),
            'referer'    => request()->headers->get('referer'),
            'user_agent' => request()->userAgent(),
            'x-livewire' => request()->headers->get('x-livewire'),
            'hx-request' => request()->headers->get('hx-request'),
            'turbo'      => request()->headers->get('turbo-visit'),
        ]);
    }

    public function mount(): void
    {

        $this->traceId = (string) Str::uuid();

        Log::info('[ComplianceBoard] mount()', [
            'trace_id'      => $this->traceId,
            'entreprise_id' => session('entreprise_id') ?: null,
        ]);

        $this->logReqMeta('mount');

        $this->loadStats();
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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }
    public function updatedFilterPeriode(): void
    {
        $this->resetPage();
    }
    public function updatedFilterCategorie(): void
    {
        $this->resetPage();
    }

    /** Ouvrir modal de soumission (création OU modification) */
    public function openSubmitModal(string $itemId, ?string $submissionId = null): void
    {
        $this->selectedItemForSubmit      = $itemId;
        $this->selectedSubmissionForSubmit = $submissionId; // <-- mémorise l’ID de la soumission à modifier

        Log::info('[ComplianceBoard] openSubmitModal()', [
            'trace_id'      => $this->traceId,
            'item_id'       => $itemId,
            'submission_id' => $submissionId,
        ]);

        // Demande au front d’ouvrir la modale
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

        // on laisse Bootstrap ouvrir via data-bs-*, ET on force l’ouverture au cas où :
        $this->dispatch('open-review-modal', submissionId: $submissionId);
    }

    /** Ouvrir modal d'historique */
    public function openHistoryModal(string $itemId): void
    {
        $this->selectedItemForHistory = $itemId;
        $this->dispatch('open-history-modal', itemId: $itemId);
    }

    #[On('settings-submitted')]
    #[On('settings-reviewed')]
    #[On('wizard-config-reload')]
    public function refresh(): void
    {
        $this->loadStats();
        $this->resetPage();
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
                'CategorieDomaine:id,nom_categorie',
                // lastPeriode (tous statuts) pour affichage d’état
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
