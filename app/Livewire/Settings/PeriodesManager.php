<?php
namespace App\Livewire\Settings;

use App\Models\PeriodeItem;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PeriodesManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Cible
    public ?string $itemId = null;
    public ?string $itemLabel = null;

    // Form période
    public ?string $selectedId = null;
    public ?string $debut_periode = null;
    public ?string $fin_periode   = null;
    public string  $statut = '1'; // 1 actif, 0 annulé
    public bool    $isEditing = false;

    // UI
    public string $search = '';
    public ?string $confirmingDeleteId = null;

    protected function rules(): array
    {
        return [
            'itemId'        => 'required|uuid|exists:items,id',
            'debut_periode' => 'required|date',
            'fin_periode'   => 'required|date|after_or_equal:debut_periode',
            'statut'        => 'required|in:0,1',
        ];
    }

    /** Ouvre/Recharge sur l’item sélectionné */
    #[On('open-periode-manager')]
    public function openForItem(string $id): void
    {
        $item = Item::findOrFail($id);
        $this->itemId    = $item->id;
        $this->itemLabel = $item->nom_item;
        $this->openForm();
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->debut_periode = null;
        $this->fin_periode   = null;
        $this->statut = '1';

        if ($id) {
            $p = PeriodeItem::where('item_id',$this->itemId)->findOrFail($id);
            $this->isEditing    = true;
            $this->selectedId   = $p->id;
            $this->debut_periode= optional($p->debut_periode)->format('Y-m-d');
            $this->fin_periode  = optional($p->fin_periode)->format('Y-m-d');
            $this->statut       = (string) $p->statut;
        }
    }

    /** Empêche les chevauchements (statut=1) sur le même item */
    private function passesUniqueness(): bool
    {
        if (!$this->debut_periode || !$this->fin_periode) return false;

        $conflict = PeriodeItem::where('item_id', $this->itemId)
            ->where('statut','1')
            ->when($this->selectedId, fn($q)=>$q->where('id','!=',$this->selectedId))
            ->where(function($q){
                $q->where('debut_periode','<=',$this->fin_periode)
                  ->where('fin_periode','>=',$this->debut_periode);
            })
            ->exists();

        if ($conflict) {
            $this->addError('debut_periode', 'Chevauchement détecté avec une période existante.');
            return false;
        }
        return true;
    }

    public function save(): void
    {
        $this->validate();
        if (!$this->passesUniqueness()) return;

        if ($this->isEditing && $this->selectedId) {
            $p = PeriodeItem::where('item_id',$this->itemId)->findOrFail($this->selectedId);
            $p->update([
                'debut_periode' => $this->debut_periode,
                'fin_periode'   => $this->fin_periode,
                'statut'        => $this->statut,
                'user_update_id'=> Auth::id(),
            ]);
            session()->flash('success','Période mise à jour.');
        } else {
            PeriodeItem::create([
                'item_id'       => $this->itemId,
                'debut_periode' => $this->debut_periode,
                'fin_periode'   => $this->fin_periode,
                'statut'        => $this->statut,
                'user_add_id'   => Auth::id(),
            ]);
            session()->flash('success','Période créée.');
        }

        $this->openForm();
        $this->resetPage();
        // Informe ItemsManager (pour badges/affichage)
        $this->dispatch('periodes-updated', id: $this->itemId);
    }

    /** Annuler (désactiver) une période (statut=0 et fin = today si future) */
    public function cancel(string $id): void
    {
        $p = PeriodeItem::where('item_id',$this->itemId)->findOrFail($id);
        $data = ['statut'=>'0','user_update_id'=>Auth::id()];
        if (now()->lt($p->fin_periode)) {
            $data['fin_periode'] = now()->toDateString();
        }
        $p->update($data);

        session()->flash('success','Période annulée.');
        $this->dispatch('periodes-updated', id: $this->itemId);
    }


    public function renewFrom(string $id): void
    {
        $p = PeriodeItem::where('item_id',$this->itemId)->findOrFail($id);
    
        $this->openForm(); // reset
    
        if ($p->fin_periode) {
            $this->debut_periode = $p->fin_periode->copy()->addDay()->format('Y-m-d');
            $this->fin_periode   = $p->fin_periode->copy()->addYear()->format('Y-m-d'); // ou ta logique métier
        } else {
            $this->debut_periode = now()->toDateString();
            $this->fin_periode   = now()->addYear()->toDateString();
        }
    
        $this->statut = '1';
    }
    // Confirmation suppression définitive (optionnelle)
    public function confirmDelete(string $id): void { $this->confirmingDeleteId = $id; }
    public function cancelDelete(): void { $this->confirmingDeleteId = null; }
    public function deleteConfirmed(): void
    {
        if (!$this->confirmingDeleteId) return;
        PeriodeItem::where('item_id',$this->itemId)->findOrFail($this->confirmingDeleteId)->delete();
        $deleted = $this->confirmingDeleteId;
        $this->confirmingDeleteId = null;

        session()->flash('success','Période supprimée.');
        $this->resetPage();
        if ($this->selectedId === $deleted) $this->openForm();
        $this->dispatch('periodes-updated', id: $this->itemId);
    }

    public function render()
    {
        $list = PeriodeItem::query()
            ->where('item_id', $this->itemId)
            ->when($this->search, fn($q)=>
                $q->where('debut_periode','like',"%{$this->search}%")
                  ->orWhere('fin_periode','like',"%{$this->search}%")
            )
            ->orderByDesc('debut_periode')
            ->paginate(6, pageName: 'periodes_p');

        return view('livewire.settings.periodes-manager', compact('list'));
    }
}
