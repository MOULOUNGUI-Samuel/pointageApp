<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\CategorieDomaine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

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

    public string $type = 'texte';          // texte|documents|liste|checkbox
    public array $options = [];
    // Nouveau flag
    public bool $showOptions = false;
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

    // Déclenché par le wire:click
    public function afficheButtonAjouter(): void
    {
        // On affiche uniquement si le type est liste ou checkbox
        if (in_array($this->type, ['liste', 'checkbox'], true)) {
            $this->showOptions = true;

            // Si aucune option, on initialise une première ligne
            if (empty($this->options)) {
                $this->options = [
                    ['label' => '', 'value' => '', 'position' => 1],
                ];
            }
        } else {
            $this->showOptions = false;
            $this->options = [];
        }
    }
    // Écoute automatiquement les changements du <select wire:model="type">
    public function updatedType(string $value): void
    {
        if (in_array($value, ['liste', 'checkbox'], true)) {
            // Si on passe à liste/checkbox et qu'il n'y a aucune option, on préremplit une ligne
            if (empty($this->options)) {
                $this->options = [
                    ['label' => '', 'value' => '', 'position' => 1],
                ];
            }
        } else {
            // Pour texte/documents: on masque et on vide les options
            $this->options = [];
        }
    }

    public function addOption(): void
    {
        $this->options[] = ['label' => '', 'value' => '', 'position' => count($this->options) + 1];
    }
    public function removeOption(int $index): void
    {
        if (isset($this->options[$index])) {
            array_splice($this->options, $index, 1);
            // re-numérote les positions
            foreach ($this->options as $k => &$opt) {
                $opt['position'] = $k + 1;
            }
        }
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
    }

    /** Recharge la liste des catégories */
    public function reloadCategories(): void
    {
        $this->categories = CategorieDomaine::orderBy('nom_categorie')
            ->get(['id', 'nom_categorie'])
            ->map(fn($c) => ['id' => $c->id, 'label' => $c->nom_categorie])
            ->all();
    }


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

    /** ----------------------------------------------------------- */

    protected function rules(): array
    {
        $rules = [
            'categorie_domaine_id' => 'required|uuid|exists:categorie_domaines,id',
            'type'                 => 'required|in:texte,documents,liste,checkbox',
            'nom_item'             => ['required', 'string', 'max:190', Rule::unique('items', 'nom_item')->ignore($this->selectedId)],
            'description'          => 'nullable|string|max:1000',
            'statut'               => 'required|in:0,1',
        ];

        // règles conditionnelles si options nécessaires
        if (in_array($this->type, ['liste', 'checkbox'], true)) {
            $rules['options'] = 'array|min:1';
            $rules['options.*.label'] = 'required|string|max:190';
            $rules['options.*.value'] = 'nullable|string|max:190';
            $rules['options.*.position'] = 'nullable|integer|min:1';
        }

        return $rules;
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->categorie_domaine_id = null;
        $this->type = 'texte';
        $this->nom_item = '';
        $this->description = null;
        $this->statut = '1';
        $this->options = [];

        // Toujours recharger à l’ouverture (si une autre modale a modifié la base)
        $this->reloadCategories();

        if ($id) {
            $i = Item::with('options')->findOrFail($id);
            $this->isEditing = true;
            $this->selectedId = $i->id;
            $this->categorie_domaine_id = $i->categorie_domaine_id;
            $this->type = $i->type;
            $this->nom_item = $i->nom_item;
            $this->description = $i->description;
            $this->statut = (string) $i->statut;

            if ($i->needsOptions()) {
                $this->options = $i->options->map(fn($o) => [
                    'label' => $o->label,
                    'value' => $o->value,
                    'position' => $o->position,
                ])->values()->toArray();
            }
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedId) {
            $i = Item::findOrFail($this->selectedId);
            $i->update([
                'categorie_domaine_id' => $this->categorie_domaine_id,
                'type'                 => $this->type,
                'nom_item'             => $this->nom_item,
                'description'          => $this->description,
                'statut'               => $this->statut,
                'user_update_id'       => Auth::id(),
            ]);

            // sync options
            $this->syncOptions($i);

            session()->flash('success', 'Item mis à jour.');
        } else {
            $i = Item::create([
                'categorie_domaine_id' => $this->categorie_domaine_id,
                'type'                 => $this->type,
                'nom_item'             => $this->nom_item,
                'description'          => $this->description,
                'statut'               => $this->statut,
                'user_add_id'          => Auth::id(),
            ]);

            $this->syncOptions($i);

            session()->flash('success', 'Item créé.');
        }

        $this->openForm();
        $this->resetPage();
        $this->dispatch('items-updated');
    }

    private function syncOptions(Item $i): void
    {
        // si le type n’a pas d’options -> on supprime tout
        if (!in_array($this->type, ['liste', 'checkbox'], true)) {
            $i->options()->delete();
            return;
        }

        // stratégie simple : on efface puis recrée (OK vu la petite taille)
        $i->options()->delete();

        foreach ($this->options as $k => $opt) {
            $i->options()->create([
                'kind'     => $this->type, // 'liste' ou 'checkbox'
                'label'    => $opt['label'],
                'value'    => $opt['value'] ?? null,
                'position' => ($opt['position'] ?? ($k + 1)),
                'statut'   => '1',
            ]);
        }
    }


    public function render()
    {
        $today = now()->toDateString();

        // Requête simplifiée pour déboguer
        $items = Item::query()
            ->with(['CategorieDomaine:id,nom_categorie', 'options'])
            ->withCount(['periodes as periodes_actives_count' => function ($q) use ($today) {
                $q->where('statut', '1')
                    ->whereDate('debut_periode', '<=', $today)
                    ->whereDate('fin_periode', '>=', $today);
            }])
            ->orderBy('nom_item')
            ->paginate(8, pageName: 'items_p');

        // DEBUG : Afficher le nombre d'items
        Log::info('Items Manager - Nombre items:', ['count' => $items->total()]);

        return view('livewire.settings.items-manager', compact('items'));
    }
}
