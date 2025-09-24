<?php

namespace App\Livewire\Settings;

use App\Models\Domaine;
use App\Models\CategorieDommaine;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EnterpriseConfigWizard extends Component
{
    // Étape courante
    public int $step = 1;

    // Entreprise
    public string $entrepriseId;

    // Sélections utilisateur (en mémoire)
    public array $selectedDomainIds    = []; // [uuid, ...]
    public array $selectedCategoryIds  = []; // [uuid, ...]
    public array $selectedItemIds      = []; // [uuid, ...]

    // Listes affichées (écran)
    public array $domaines   = []; // [['id'=>..,'label'=>..],...]
    public array $categories = []; // filtrées
    public array $items      = []; // filtrés

    // Recherches simples
    public string $searchDomaines   = '';
    public string $searchCategories = '';
    public string $searchItems      = '';

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');

        // Précharger sélections existantes depuis pivots
        $this->selectedDomainIds = DB::table('entreprise_domaines')
            ->where('entreprise_id', $this->entrepriseId)->pluck('domaine_id')->all();

        $this->selectedCategoryIds = DB::table('entreprise_categorie_domaines')
            ->where('entreprise_id', $this->entrepriseId)->pluck('categorie_domaine_id')->all();
        $this->selectedItemIds = DB::table('entreprise_items')
            ->where('entreprise_id', $this->entrepriseId)->pluck('item_id')->all();

        // Charger la 1ère page
        $this->loadDomaines();
        $this->loadCategories(); // affichera vide tant que pas de domaines
        $this->loadItems();      // affichera vide tant que pas de catégories
    }
    private function notifyBoard(): void
    {
        $this->dispatch('wizard-config-updated');
    }
    /** ======= LOADERS ======= */
    public function loadDomaines(): void
    {
        $q = Domaine::query()->orderBy('nom_domaine');
        if ($this->searchDomaines !== '') {
            $q->where('nom_domaine', 'like', "%{$this->searchDomaines}%");
        }
        $this->domaines = $q->get(['id','nom_domaine'])
            ->map(fn($d)=>['id'=>$d->id,'label'=>$d->nom_domaine])
            ->all();
    }

    public function loadCategories(): void
    {
        if (empty($this->selectedDomainIds)) {
            $this->categories = [];
            return;
        }

        $q = CategorieDommaine::whereIn('domaine_id', $this->selectedDomainIds)
            ->orderBy('nom_categorie');

        if ($this->searchCategories !== '') {
            $q->where('nom_categorie', 'like', "%{$this->searchCategories}%");
        }

        $this->categories = $q->get(['id','nom_categorie','domaine_id'])
            ->map(fn($c)=>['id'=>$c->id,'label'=>$c->nom_categorie,'domaine_id'=>$c->domaine_id])
            ->all();
    }

    public function loadItems(): void
    {
        if (empty($this->selectedCategoryIds)) {
            $this->items = [];
            return;
        }

        $q = Item::whereIn('categorie_domaine_id', $this->selectedCategoryIds)
            ->orderBy('nom_item');

        if ($this->searchItems !== '') {
            $q->where('nom_item', 'like', "%{$this->searchItems}%");
        }

        $this->items = $q->get(['id','nom_item','categorie_domaine_id'])
            ->map(fn($i)=>['id'=>$i->id,'label'=>$i->nom_item,'categorie_id'=>$i->categorie_domaine_id])
            ->all();
    }

    /** ======= TOGGLES ======= */
    public function toggleDomaine(string $id): void
    {
        if (in_array($id, $this->selectedDomainIds, true)) {
            $this->selectedDomainIds = array_values(array_diff($this->selectedDomainIds, [$id]));
            // Enlever les catégories et items qui ne rentrent plus dans le filtre
            $this->selectedCategoryIds = array_values(
                array_filter($this->selectedCategoryIds, function($catId) {
                    $cat = CategorieDommaine::find($catId);
                    return $cat && in_array($cat->domaine_id, $this->selectedDomainIds, true);
                })
            );
            $this->selectedItemIds = array_values(
                array_filter($this->selectedItemIds, function($itemId) {
                    $item = Item::find($itemId);
                    return $item && in_array($item->categorie_domaine_id, $this->selectedCategoryIds, true);
                })
            );
        } else {
            $this->selectedDomainIds[] = $id;
        }
        $this->loadCategories();
        $this->loadItems();
    }

    public function toggleCategorie(string $id): void
    {
        if (in_array($id, $this->selectedCategoryIds, true)) {
            $this->selectedCategoryIds = array_values(array_diff($this->selectedCategoryIds, [$id]));
            // Déselectionner items orphelins
            $this->selectedItemIds = array_values(
                array_filter($this->selectedItemIds, function($itemId) {
                    $item = Item::find($itemId);
                    return $item && in_array($item->categorie_domaine_id, $this->selectedCategoryIds, true);
                })
            );
        } else {
            $this->selectedCategoryIds[] = $id;
        }
        $this->loadItems();
    }

    public function toggleItem(string $id): void
    {
        if (in_array($id, $this->selectedItemIds, true)) {
            $this->selectedItemIds = array_values(array_diff($this->selectedItemIds, [$id]));
        } else {
            $this->selectedItemIds[] = $id;
        }
    }

    /** ======= NAVIGATION ======= */
    public function nextFromDomains(): void
    {
        // persiste domaines
        $this->syncEntrepriseDomaines();
        $this->notifyBoard();
        $this->step = 2;
        $this->loadCategories();
    }

    public function backToDomains(): void
    {
        $this->step = 1;
        $this->loadDomaines();
    }

    public function nextFromCategories(): void
    {
        // persiste catégories
        $this->syncEntrepriseCategories();
        $this->notifyBoard();
        $this->step = 3;
        $this->loadItems();
    }

    public function backToCategories(): void
    {
        $this->step = 2;
        $this->loadCategories();
    }

    public function finish(): void
    {
        // persiste items
        $this->syncEntrepriseItems();
        $this->notifyBoard();
        session()->flash('success', 'Configuration enregistrée ✅');
        // Tu peux rester sur l’étape 3 ou revenir à 1 selon ton UX
    }

    /** ======= PERSISTENCE ======= */
    private function syncEntrepriseDomaines(): void
    {
        // stratégie simple : purge puis insert
        DB::table('entreprise_domaines')->where('entreprise_id', $this->entrepriseId)->delete();

        $rows = array_map(fn($id)=>[
            'entreprise_id' => $this->entrepriseId,
            'domaine_id'    => $id,
            'statut'        => '1',
            'created_at'    => now(),
            'updated_at'    => now(),
        ], $this->selectedDomainIds);

        if ($rows) DB::table('entreprise_domaines')->insert($rows);
    }

    private function syncEntrepriseCategories(): void
    {
        DB::table('entreprise_categorie_domaines')->where('entreprise_id', $this->entrepriseId)->delete();

        $rows = array_map(fn($id)=>[
            'entreprise_id'       => $this->entrepriseId,
            'categorie_domaine_id'=> $id,
            'statut'              => '1',
            'created_at'          => now(),
            'updated_at'          => now(),
        ], $this->selectedCategoryIds);

        if ($rows) DB::table('entreprise_categorie_domaines')->insert($rows);
    }

    private function syncEntrepriseItems(): void
    {
        DB::table('entreprise_items')->where('entreprise_id', $this->entrepriseId)->delete();

        $rows = array_map(fn($id)=>[
            'entreprise_id' => $this->entrepriseId,
            'item_id'       => $id,
            'statut'        => '1',
            'created_at'    => now(),
            'updated_at'    => now(),
        ], $this->selectedItemIds);

        if ($rows) DB::table('entreprise_items')->insert($rows);
    }

    /** Recherches (événements input) */
    public function updatedSearchDomaines()   { $this->loadDomaines(); }
    public function updatedSearchCategories() { $this->loadCategories(); }
    public function updatedSearchItems()      { $this->loadItems(); }

    public function render()
    {
        return view('livewire.settings.enterprise-config-wizard');
    }
}
