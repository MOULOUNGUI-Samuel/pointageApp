<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\TypeItem;
use App\Models\CategorieDommaine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ItemsManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Entreprise (si besoin ailleurs)
    public string $entrepriseId;

    // Form
    public ?string $selectedId = null;
    public ?string $categorie_domaine_id = null;
    public ?string $type_item_id = null;
    public string  $nom_item = '';
    public ?string $description = null;
    public string  $statut = '1';
    public bool    $isEditing = false;

    // Selects dynamiques
    public array $categories = [];
    public array $types      = [];

    // UI
    public string  $search = '';
    public ?string $confirmingDeleteId = null;

    /** ----- Confirm delete flow ----- */
    public function confirmDelete(string $id): void
    {
        $this->confirmingDeleteId = $id;
    }
    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }
    public function deleteConfirmed(): void
    {
        if (!$this->confirmingDeleteId) return;

        Item::findOrFail($this->confirmingDeleteId)->delete();
        $deletedId = $this->confirmingDeleteId;
        $this->confirmingDeleteId = null;

        session()->flash('success', 'Item supprimé.');
        $this->resetPage();
        if ($this->selectedId === $deletedId) {
            $this->openForm();
        }
        $this->dispatch('items-updated');
    }

    /** Ouvrir la modale des périodes sur l’item */
    public function openPeriodeManager(string $itemId): void
    {
        $this->dispatch('open-periode-manager', id: $itemId); // => PeriodesManager
        $this->dispatch('showPeriodeModal');                  // => si tu as un JS qui ouvre la modale
    }

    /** Après maj des périodes, on rafraîchit la liste */
    #[On('periodes-updated')]
    public function onPeriodesUpdated(string $id): void
    {
        $this->resetPage();
    }
    

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');
        $this->reloadCategories();
        $this->reloadTypes();
    }

    /** Recharge la liste des catégories */
    public function reloadCategories(): void
    {
        $this->categories = CategorieDommaine::orderBy('nom_categorie')
            ->get(['id', 'nom_categorie'])
            ->map(fn($c) => ['id' => $c->id, 'label' => $c->nom_categorie])
            ->all();
    }

    /** Recharge la liste des types */
    public function reloadTypes(): void
    {
        $this->types = TypeItem::orderBy('nom_type')
            ->get(['id', 'nom_type'])
            ->map(fn($t) => ['id' => $t->id, 'label' => $t->nom_type])
            ->all();
    }

    /** ------ Listeners de synchronisation (catégories/types) ------ */

    #[On('categories-updated')]
    public function onCategoriesUpdated(): void
    {
        $this->reloadCategories();
    }

    #[On('categories-deleted')]
    public function onCategoriesDeleted(string $id): void
    {
        $this->reloadCategories();
        if ($this->categorie_domaine_id === $id) {
            $this->categorie_domaine_id = null;
        }
    }

    #[On('types-updated')]
    public function onTypesUpdated(): void
    {
        $this->reloadTypes();
    }

    #[On('types-deleted')]
    public function onTypesDeleted(string $id): void
    {
        $this->reloadTypes();
        if ($this->type_item_id === $id) {
            $this->type_item_id = null;
        }
    }

    /** ----------------------------------------------------------- */

    protected function rules(): array
    {
        return [
            'categorie_domaine_id' => 'required|uuid|exists:categorie_domaines,id',
            'type_item_id'         => 'required|uuid|exists:type_items,id',
            'nom_item'             => [
                'required',
                'string',
                'max:190',
                Rule::unique('items', 'nom_item')->ignore($this->selectedId),
            ],
            'description'          => 'nullable|string|max:1000',
            'statut'               => 'required|in:0,1',
        ];
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->categorie_domaine_id = null;
        $this->type_item_id = null;
        $this->nom_item = '';
        $this->description = null;
        $this->statut = '1';

        // Toujours recharger à l’ouverture (si une autre modale a modifié la base)
        $this->reloadCategories();
        $this->reloadTypes();

        if ($id) {
            $i = Item::findOrFail($id);
            $this->isEditing = true;
            $this->selectedId = $i->id;
            $this->categorie_domaine_id = $i->categorie_domaine_id;
            $this->type_item_id = $i->type_item_id;
            $this->nom_item = $i->nom_item;
            $this->description = $i->description;
            $this->statut = (string) $i->statut;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedId) {
            $i = Item::findOrFail($this->selectedId);
            $i->update([
                'categorie_domaine_id' => $this->categorie_domaine_id,
                'type_item_id'         => $this->type_item_id,
                'nom_item'             => $this->nom_item,
                'description'          => $this->description,
                'statut'               => $this->statut,
                'user_update_id'       => Auth::id(),
            ]);
            session()->flash('success', 'Item mis à jour.');
        } else {
            Item::create([
                'categorie_domaine_id' => $this->categorie_domaine_id,
                'type_item_id'         => $this->type_item_id,
                'nom_item'             => $this->nom_item,
                'description'          => $this->description,
                'statut'               => $this->statut,
                'user_add_id'          => Auth::id(),
            ]);
            session()->flash('success', 'Item créé.');
        }

        $this->openForm();
        $this->resetPage();
        $this->dispatch('items-updated');
    }

    public function render()
{
    $today = now()->toDateString();

    $items = Item::query()
        ->with([
            'categorieDommaine:id,nom_categorie',
            'typeItem:id,nom_type',
            'periodeActive',                     // <- relation hasOne filtrée
        ])
        ->withCount([
            'periodes as periodes_actives_count' => function ($q) use ($today) {
                $q->where('statut', '1')
                  ->whereDate('debut_periode', '<=', $today)
                  ->whereDate('fin_periode',   '>=', $today);
            },
        ])
        ->orderBy('nom_item')
        ->paginate(8, pageName: 'items_p');

    return view('livewire.settings.items-manager', compact('items'));
}

}
