<?php

namespace App\Livewire\Settings;

use App\Models\TypeItem;
use Livewire\Component;
use Livewire\WithPagination;

class TypeItemsManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?string $selectedId = null;
    public string $nom_type = '';
    public ?string $description = null;
    public string $statut = '1';
    public bool $isEditing = false;

    public string $search = '';

    public ?string $confirmingDeleteId = null;

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
        TypeItem::findOrFail($this->confirmingDeleteId)->delete();
        $deletedId = $this->confirmingDeleteId;
        $this->confirmingDeleteId = null;
        session()->flash('success', 'Enregistrement supprimé.');
        $this->resetPage();
        if ($this->selectedId === $deletedId) $this->openForm();
        $this->dispatch('types-updated');
        $this->dispatch('types-deleted', id: $deletedId);
    }
    protected function rules(): array
    {
        return [
            'nom_type'   => 'required|string|max:190',
            'description' => 'nullable|string|max:1000',
            'statut'     => 'required|in:0,1',
        ];
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->nom_type = '';
        $this->description = null;
        $this->statut = '1';

        if ($id) {
            $t = TypeItem::findOrFail($id);
            $this->isEditing = true;
            $this->selectedId = $t->id;
            $this->nom_type = $t->nom_type;
            $this->description = $t->description;
            $this->statut = (string) $t->statut;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedId) {
            $t = TypeItem::findOrFail($this->selectedId);
            $t->update([
                'nom_type'    => $this->nom_type,
                'description' => $this->description,
                'statut'      => $this->statut,
            ]);
            session()->flash('success', 'Type mis à jour.');
        } else {
            TypeItem::create([
                'nom_type'    => $this->nom_type,
                'description' => $this->description,
                'statut'      => $this->statut,
            ]);
            session()->flash('success', 'Type créé.');
        }

        $this->openForm();
        $this->resetPage();
        $this->dispatch('types-updated');
    }


    public function render()
    {
        $items = TypeItem::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('nom_type', 'like', "%{$this->search}%")
            )
            ->orderBy('nom_type')
            ->paginate(8, pageName: 'types_p');

        return view('livewire.settings.type-items-manager', compact('items'));
    }
}
