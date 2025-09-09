<?php

namespace App\Livewire\Settings;

use App\Models\Domaine;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DomainesManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Entreprise scope
    // public string $entrepriseId;

    // Form
    public ?string $selectedId = null;
    public string $nom_domaine = '';
    public ?string $description = null;
    public string $statut = '1';
    public bool $isEditing = false;

    public ?string $confirmingDeleteId = null;

    public function confirmDelete(string $id): void { $this->confirmingDeleteId = $id; }
    public function cancelDelete(): void { $this->confirmingDeleteId = null; }
    public function deleteConfirmed(): void
    {
        if (!$this->confirmingDeleteId) return;

        $d = Domaine::findOrFail($this->confirmingDeleteId);
        $d->delete();

        $deletedId = $this->confirmingDeleteId;
        $this->confirmingDeleteId = null;

        session()->flash('success', 'Domaine supprimé.');
        $this->resetPage();
        if ($this->selectedId === $deletedId) {
            $this->openForm();
        }

        // 👉 très important pour CategoriesManager
        $this->dispatch('domaines-updated');
        $this->dispatch('domaines-deleted', id: $deletedId);
    }
    
    // Filtres
    public string $search = '';

    // public function mount(): void
    // {
    //     $this->entrepriseId = (string) session('entreprise_id');
    // }

    protected function rules(): array
    {
        return [
            'nom_domaine' => 'required|string|max:190',
            'description' => 'nullable|string|max:1000',
            'statut'      => 'required|in:0,1',
        ];
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->nom_domaine = '';
        $this->description = null;
        $this->statut = '1';

        if ($id) {
            $d = Domaine::findOrFail($id);
            $this->isEditing = true;
            $this->selectedId = $d->id;
            $this->nom_domaine = $d->nom_domaine;
            $this->description = $d->description;
            $this->statut = (string) $d->statut;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedId) {
            $d = Domaine::findOrFail($this->selectedId);
            $d->update([
                'nom_domaine'   => $this->nom_domaine,
                'description'   => $this->description,
                'statut'        => $this->statut,
                'user_update_id'=> Auth::id(),
            ]);
            session()->flash('success', 'Domaine mis à jour.');
        } else {
            Domaine::create([
                'user_add_id'   => Auth::id(),
                'nom_domaine'   => $this->nom_domaine,
                'description'   => $this->description,
                'statut'        => $this->statut,
            ]);
            session()->flash('success', 'Domaine créé.');
        }

        $this->openForm(); // reset
        $this->resetPage();
        $this->dispatch('domaines-updated');
    }

    public function render()
    {
        $items = Domaine::query()
            ->when($this->search, fn($q) =>
                $q->where('nom_domaine','like',"%{$this->search}%")
                  ->orWhere('description','like',"%{$this->search}%")
            )
            ->orderBy('nom_domaine')
            ->paginate(8, pageName: 'domaines_p');

        return view('livewire.settings.domaines-manager', compact('items'));
    }
}
