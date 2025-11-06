<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Domaine;
use App\Models\CategorieDommaine;
use App\Models\Item;
use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;

class EnterpriseConfigBoard extends Component
{
    public string $entrepriseId;

    // Sélection courante (UI)
    public ?string $selectedDomainId   = null;
    public ?string $selectedCategoryId = null;

    // Données écran
    public array $domains     = []; // [['id','label']]
    public array $categories  = []; // [['id','label']]
    public $items;                  // Collection

    // Filtres
    public string $searchCategory = '';
    public string $searchItem     = '';

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');

        $this->loadDomains();
        $this->autoloadFirsts();
        $this->loadCategories();
        $this->loadItems();
    }

    private function autoloadFirsts(): void
    {
        if (!$this->selectedDomainId && !empty($this->domains)) {
            $this->selectedDomainId = $this->domains[0]['id'];
        }
        if (!$this->selectedCategoryId) {
            $this->selectedCategoryId = $this->firstCategoryIdForDomain($this->selectedDomainId);
        }
    }

    private function ensureSelectionsExist(): void
    {
        $domainIds = collect($this->domains)->pluck('id');
        if ($this->selectedDomainId && !$domainIds->contains($this->selectedDomainId)) {
            $this->selectedDomainId = $this->domains[0]['id'] ?? null;
        }

        $categoryIds = collect($this->categories)->pluck('id');
        if ($this->selectedCategoryId && !$categoryIds->contains($this->selectedCategoryId)) {
            $this->selectedCategoryId = $this->categories[0]['id'] ?? null;
        }
    }

    /** ————— EVENTS ————— */

    /** Recharger quand le wizard demande un refresh global */
    #[On('wizard-config-reload')]
    public function onWizardReload(): void
    {
        $this->loadDomains();
        $this->loadCategories();
        $this->ensureSelectionsExist();
        $this->loadItems();

        session()->flash('success', 'Assistant rafraîchi ✅');
    }

    /** Recharger les items si les périodes ont changé (depuis le manager de périodes) */
    #[On('periodes-updated')]
    public function onPeriodesUpdated(): void
    {
        $this->loadItems();
    }

    /** Recharger quand une soumission est faite ou évaluée */
    #[On('settings-submitted')]
    #[On('settings-reviewed')]
    public function refreshFromChild(): void
    {
        $this->onWizardReload();
    }

    /** ————— HELPERS DATA ————— */

    private function firstCategoryIdForDomain(?string $domainId): ?string
    {
        if (!$domainId) return null;

        $first = CategorieDommaine::query()
            ->join('entreprise_categorie_domaines as ecd', 'ecd.categorie_domaine_id', '=', 'categorie_domaines.id')
            ->where('ecd.entreprise_id', $this->entrepriseId)
            ->where('categorie_domaines.domaine_id', $domainId)
            ->orderBy('categorie_domaines.nom_categorie')
            ->value('categorie_domaines.id');

        return $first ?: null;
    }

    /** Domaines activés pour l’entreprise */
    public function loadDomains(): void
    {
        $rows = Domaine::query()
            ->join('entreprise_domaines as ed', 'ed.domaine_id', '=', 'domaines.id')
            ->where('ed.entreprise_id', $this->entrepriseId)
            ->where('ed.statut', '1')
            ->orderBy('domaines.nom_domaine')
            ->get(['domaines.id','domaines.nom_domaine']);

        $this->domains = $rows->map(fn($d) => ['id'=>$d->id,'label'=>$d->nom_domaine])->all();
    }


    /** Catégories activées pour le domaine sélectionné */
    public function loadCategories(): void
    {
        if (!$this->selectedDomainId) {
            $this->categories = [];
            return;
        }

        $q = CategorieDommaine::query()
            ->join('entreprise_categorie_domaines as ecd', 'ecd.categorie_domaine_id', '=', 'categorie_domaines.id')
            ->where('ecd.entreprise_id', $this->entrepriseId)
            ->where('ecd.statut', '1')
            ->where('categorie_domaines.domaine_id', $this->selectedDomainId)
            ->orderBy('categorie_domaines.nom_categorie');

        if ($this->searchCategory !== '') {
            $q->where('categorie_domaines.nom_categorie', 'like', "%{$this->searchCategory}%");
        }

        $this->categories = $q->get(['categorie_domaines.id','categorie_domaines.nom_categorie'])
            ->map(fn($c)=>['id'=>$c->id,'label'=>$c->nom_categorie])
            ->all();

        // sécuriser la sélection si elle n’existe plus
        if ($this->selectedCategoryId && !collect($this->categories)->pluck('id')->contains($this->selectedCategoryId)) {
            $this->selectedCategoryId = $this->categories[0]['id'] ?? null;
        }
    }

    /** Nom du domaine sélectionné */
    public function getSelectedDomainName(): ?string
    {
        $domain = collect($this->domains)->firstWhere('id', $this->selectedDomainId);
        return $domain['label'] ?? null;
    }

    /** Items activés pour la catégorie sélectionnée */
    public function loadItems(): void
    {
        if (!$this->selectedCategoryId) {
            $this->items = collect();
            return;
        }

        $today = now()->toDateString();
        $eid   = $this->entrepriseId;

        // Mettre à jour le statut des périodes expirées
        PeriodeItem::where('entreprise_id', $eid)
            ->where('statut', '1')
            ->whereDate('fin_periode', '<', $today)
            ->update(['statut' => '0']);

        // Récupérer la dernière date de fin de période active
        $latestActiveEndDate = PeriodeItem::where('entreprise_id', $eid)
            ->where('statut', '1')
            ->orderByDesc('fin_periode')
            ->value('fin_periode');

        $q = Item::query()
            ->join('entreprise_items as ei', 'ei.item_id', '=', 'items.id')
            ->where('ei.entreprise_id', $eid)
            ->where('ei.statut', '1')
            ->where('items.categorie_domaine_id', $this->selectedCategoryId)

            // PÉRIODE ACTIVE (bool)
            ->withExists([
                'periodes as periode_active' => function ($qq) use ($today, $eid) {
                    $qq->where('entreprise_id', $eid)
                       ->where('statut', '1')
                       ->whereDate('debut_periode', '<=', $today)
                       ->whereDate('fin_periode',   '>=', $today);
                }
            ])

            // Dernière période / période active / décomptes pour CETTE entreprise
            ->addSelect([
                'latest_debut' => PeriodeItem::select('debut_periode')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('fin_periode')
                    ->limit(1),

                'latest_fin' => PeriodeItem::select('fin_periode')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('fin_periode')
                    ->limit(1),

                'active_periode_id' => PeriodeItem::select('id')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->where('statut', '1')
                    ->whereDate('debut_periode', '<=', $today)
                    ->whereDate('fin_periode', '>=', $today)
                    ->orderByDesc('fin_periode')
                    ->limit(1),

                'active_fin' => PeriodeItem::select('fin_periode')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->where('statut', '1')
                    ->limit(1),

                'periodes_count_for_ent' => PeriodeItem::selectRaw('count(*)')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid),

                // Flags UX supplémentaires
                'active_expires_soon' => PeriodeItem::selectRaw(
                    "CASE WHEN DATEDIFF(fin_periode, ?) BETWEEN 0 AND 15 THEN 1 ELSE 0 END", [$today]
                )
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->where('statut', '1')
                    ->whereDate('debut_periode', '<=', $today)
                    ->whereDate('fin_periode', '>=', $today)
                    ->latest('fin_periode')
                    ->limit(1),

                'is_expired' => PeriodeItem::selectRaw(
                    "CASE WHEN fin_periode < ? THEN 1 ELSE 0 END", [$today]
                )
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->latest('fin_periode')
                    ->limit(1),
            ])

            // Statut de conformité : dernière soumission / pending
            ->addSelect([
                'latest_submission_id' => ConformitySubmission::select('id')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('created_at')
                    ->limit(1),

                'latest_submission_status' => ConformitySubmission::select('status')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('created_at')
                    ->limit(1),

                'latest_submission_review_notes' => ConformitySubmission::select('reviewer_notes')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('created_at')
                    ->limit(1),

                'latest_submission_reviewed_at' => ConformitySubmission::select('reviewed_at')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('created_at')
                    ->limit(1),

                'latest_submission_submitted_at' => ConformitySubmission::select('submitted_at')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->orderByDesc('created_at')
                    ->limit(1),

                'latest_pending_submission_id' => ConformitySubmission::select('id')
                    ->whereColumn('item_id', 'items.id')
                    ->where('entreprise_id', $eid)
                    ->where('status', 'soumis')
                    ->orderByDesc('created_at')
                    ->limit(1),
            ])
            ->orderBy('items.nom_item');

        if ($this->searchItem !== '') {
            $q->where('items.nom_item', 'like', "%{$this->searchItem}%");
        }

        $this->items = $q->get(['items.*']);
    }
    /** Nom de la catégorie sélectionnée */
    public function getSelectedCategoryName(): ?string
    {
        $category = collect($this->categories)->firstWhere('id', $this->selectedCategoryId);
        return $category['label'] ?? null;
    }

    /** ————— ACTIONS UI ————— */

    public function selectDomain(string $domainId): void
    {
        $this->selectedDomainId   = $domainId;
        $this->selectedCategoryId = $this->firstCategoryIdForDomain($domainId);
        $this->loadCategories();

        if (!$this->selectedCategoryId) {
            $this->items = collect();
            return;
        }
        $this->loadItems();
    }

    public function selectCategory(string $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
        $this->loadItems();
    }

    public function toggleItem(string $itemId): void
    {
        $row = DB::table('entreprise_items')
            ->where('entreprise_id', $this->entrepriseId)
            ->where('item_id', $itemId)
            ->first();

        if (!$row) {
            DB::table('entreprise_items')->insert([
                'entreprise_id' => $this->entrepriseId,
                'item_id'       => $itemId,
                'statut'        => '1',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        } else {
            DB::table('entreprise_items')
                ->where('entreprise_id', $this->entrepriseId)
                ->where('item_id', $itemId)
                ->update([
                    'statut'     => $row->statut === '1' ? '0' : '1',
                    'updated_at' => now(),
                ]);
        }

        $this->loadItems();
    }

    /** Wizard a sauvegardé une étape */
    #[On('wizard-config-updated')]
    public function onWizardUpdated(): void
    {
        $this->loadDomains();
        $this->autoloadFirsts();
        $this->loadCategories();
        $this->loadItems();
    }

    public function updatedSearchCategory() { $this->loadCategories(); }
    public function updatedSearchItem()     { $this->loadItems(); }

    public function render()
    {
        return view('livewire.settings.enterprise-config-board');
    }
}
