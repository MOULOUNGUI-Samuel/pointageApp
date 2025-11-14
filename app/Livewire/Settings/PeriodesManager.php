<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\PeriodeItem;
use App\Models\Item;

class PeriodesManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Contexte
    public ?string $itemId    = null;
    public ?string $itemLabel = null;

    // Form
    public ?string $selectedId     = null;
    public ?string $debut_periode  = null;
    public ?string $fin_periode    = null;
    public string  $statut         = '1';
    public bool    $isEditing      = false;

    // UI
    public string  $search             = '';
    public ?string $confirmingDeleteId = null;

    // Entreprise
    public string $entrepriseId;

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');
    }

    protected function rules(): array
    {
        return [
            'itemId'        => 'required|uuid|exists:items,id',
            'debut_periode' => 'required|date',
            'fin_periode'   => 'required|date|after_or_equal:debut_periode',
            'statut'        => 'required|in:0,1',
        ];
    }

    /** Ouvrir le manager pour un item donné */
    #[On('open-periode-manager')]
    public function openForItem(string $id): void
    {
        $item = Item::findOrFail($id);
        $this->itemId    = $item->id;
        $this->itemLabel = $item->nom_item;
        $this->resetPage();
        $this->openForm();
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing      = false;
        $this->selectedId     = null;
        $this->debut_periode  = null;
        $this->fin_periode    = null;
        $this->statut         = '1';

        if ($id) {
            $p = PeriodeItem::where('item_id', $this->itemId)
                ->where('entreprise_id', $this->entrepriseId)
                ->findOrFail($id);

            $this->isEditing      = true;
            $this->selectedId     = $p->id;
            $this->debut_periode  = optional($p->debut_periode)->format('Y-m-d');
            $this->fin_periode    = optional($p->fin_periode)->format('Y-m-d');
            $this->statut         = (string) $p->statut;
        }
    }

    /** Unicité des actifs (pas de chevauchement) */
    private function passesUniqueness(): bool
    {
        if (!$this->debut_periode || !$this->fin_periode) return false;

        $conflict = PeriodeItem::where('item_id', $this->itemId)
            ->where('entreprise_id', $this->entrepriseId)
            ->where('statut', '1')
            ->when($this->selectedId, fn($q) => $q->where('id', '!=', $this->selectedId))
            ->where(function ($q) {
                $q->where('debut_periode', '<=', $this->fin_periode)
                    ->where('fin_periode',   '>=', $this->debut_periode);
            })
            ->exists();

        if ($conflict) {
            $this->dispatch('notify', type: 'error', message: 'Chevauchement détecté avec une période active existante.');
            $this->addError('debut_periode', 'Chevauchement détecté avec une période active existante.');
            return false;
        }
        return true;
    }

    public function save(): void
    {
        $this->validate();
        if (!$this->passesUniqueness()) return;

        if (PeriodeItem::hasActive($this->itemId, $this->entrepriseId)) {
            $this->dispatch('notify', type: 'error', message: 'Une période active existe déjà pour cet item.');
        }
        if ($this->isEditing && $this->selectedId) {
            $p = PeriodeItem::where('item_id', $this->itemId)
                ->where('entreprise_id', $this->entrepriseId)
                ->findOrFail($this->selectedId);

            $p->update([
                'debut_periode'  => $this->debut_periode,
                'fin_periode'    => $this->fin_periode,
                'statut'         => $this->statut,
                'user_update_id' => Auth::id(),
            ]);

            $this->dispatch('notify', type: 'success', message: 'Période mise à jour.');
            // Rafraîchir le compliance-board
            $this->dispatch('wizard-config-reload')->to('settings.compliance-board');
            // Event Livewire pour prévenir le parent / autres composants
            $this->dispatch('periode-creee', id: $p->id);
            // Event pour rafraîchir le board parent
            $this->dispatch('wizard-config-reload');
        } else {
            PeriodeItem::create([
                'item_id'        => $this->itemId,
                'entreprise_id'  => $this->entrepriseId,
                'debut_periode'  => $this->debut_periode,
                'fin_periode'    => $this->fin_periode,
                'statut'         => $this->statut,
                'user_add_id'    => Auth::id(),
            ]);

            $this->dispatch('notify', type: 'success', message: 'Période créée.');
            // Rafraîchir le compliance-board
            $this->dispatch('wizard-config-reload')->to('settings.compliance-board');
        }

        $this->openForm();
        $this->resetPage();

        $this->dispatch('periodes-updated', id: $this->itemId);
        $this->dispatch('wizard-config-reload');
    }

    public function cancel(string $id): void
    {
        $p = PeriodeItem::where('item_id', $this->itemId)
            ->where('entreprise_id', $this->entrepriseId)
            ->findOrFail($id);

        $data = ['statut' => '0', 'user_update_id' => Auth::id()];
        if ($p->fin_periode && now()->lt($p->fin_periode)) {
            $data['fin_periode'] = now()->toDateString();
        }

        $p->update($data);

        session()->flash('success', 'Période annulée.');
        $this->dispatch('periodes-updated', id: $this->itemId);
        $this->dispatch('wizard-config-reload');
        // Rafraîchir le compliance-board
        $this->dispatch('wizard-config-reload')->to('settings.compliance-board');
    }

    public function renewFrom(string $id): void
    {
        $p = PeriodeItem::where('item_id', $this->itemId)
            ->where('entreprise_id', $this->entrepriseId)
            ->findOrFail($id);

        $this->openForm(); // reset

        if ($p->fin_periode) {
            $this->debut_periode = $p->fin_periode->copy()->addDay()->format('Y-m-d');
            $this->fin_periode   = $p->fin_periode->copy()->addYear()->format('Y-m-d');
        } else {
            $this->debut_periode = now()->toDateString();
            $this->fin_periode   = now()->addYear()->toDateString();
        }
        $this->statut = '1';
    }

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

        PeriodeItem::where('item_id', $this->itemId)
            ->where('entreprise_id', $this->entrepriseId)
            ->findOrFail($this->confirmingDeleteId)
            ->delete();

        $deleted = $this->confirmingDeleteId;
        $this->confirmingDeleteId = null;

        session()->flash('success', 'Période supprimée.');
        $this->resetPage();
        if ($this->selectedId === $deleted) {
            $this->openForm();
        }

        $this->dispatch('periodes-updated', id: $this->itemId);
        $this->dispatch('wizard-config-reload');
        // Rafraîchir le compliance-board
        $this->dispatch('wizard-config-reload')->to('settings.compliance-board');
    }

    /** Reset pagination quand on modifie la recherche */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Tant que l’item n’est pas fixé, on ne requête pas
        if (!$this->itemId) {
            return view('livewire.settings.periodes-manager', ['list' => collect()]);
        }

        $list = PeriodeItem::query()
            ->where('item_id', $this->itemId)
            ->where('entreprise_id', $this->entrepriseId)
            ->when($this->search !== '', function ($q) {
                $s = trim($this->search);
                $q->where(function ($qq) use ($s) {
                    $qq->where('debut_periode', 'like', "%{$s}%")
                        ->orWhere('fin_periode',   'like', "%{$s}%");
                });
            })
            ->orderByDesc('debut_periode')
            ->paginate(6, pageName: 'periodes_p');

        return view('livewire.settings.periodes-manager', compact('list'));
    }
}
