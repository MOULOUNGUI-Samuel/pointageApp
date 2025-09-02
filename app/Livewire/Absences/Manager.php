<?php

// app/Livewire/Absences/Manager.php
namespace App\Livewire\Absences;

use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Manager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $modalOpen = false;
    public $isEditing = false;
    public $selectedId = null;

    // Champs formulaire
    public $type = 'congé_payé';
    public $start_datetime;
    public $end_datetime;
    public $reason;
    public $justification; // utilisé surtout pour rejet

    protected function rules()
    {
        return [
            'type'           => ['required', 'in:congé_payé,maladie,RTT,autre'],
            'start_datetime' => ['required', 'date'],
            'end_datetime'   => ['required', 'date', 'after:start_datetime'],
            'reason'         => ['nullable', 'string', 'max:5000'],
            'justification'  => ['nullable', 'string', 'max:8000'],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->resetForm();

        if ($id) {
            $this->isEditing = true;
            $this->selectedId = $id;
            $a = \App\Models\Absence::where('user_id', auth()->id())->findOrFail($id);
            $this->type = $a->type;
            $this->start_datetime = optional($a->start_datetime)->format('Y-m-d\TH:i');
            $this->end_datetime   = optional($a->end_datetime)->format('Y-m-d\TH:i');
            $this->reason = $a->reason;
        } else {
            $this->isEditing = false;
            $this->selectedId = null;
        }
    }


    public function save()
    {
        $data = $this->validate();

        if ($this->isEditing && $this->selectedId) {
            $a = Absence::mine()->findOrFail($this->selectedId);
            // En édition on revient en brouillon si la période change
            $a->update([
                'type' => $data['type'],
                'start_datetime' => $data['start_datetime'],
                'end_datetime'   => $data['end_datetime'],
                'reason'         => $data['reason'] ?? null,
                'status'         => $a->status === 'approuvé' ? 'soumis' : $a->status,
            ]);
            session()->flash('success', 'Demande mise à jour.');
        } else {
            Absence::create([
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'start_datetime' => $data['start_datetime'],
                'end_datetime'   => $data['end_datetime'],
                'reason'         => $data['reason'] ?? null,
                'status'         => 'brouillon',
            ]);
            session()->flash('success', 'Demande créée en brouillon.');
        }

        $this->modalOpen = false;
        $this->resetForm();
    }

    public function submit($id)
    {
        $a = Absence::mine()->findOrFail($id);
        // On ne peut soumettre qu’un brouillon
        if ($a->status === 'brouillon') {
            $a->update(['status' => 'soumis']);
            session()->flash('success', 'Demande soumise.');
        }
    }

    public function delete($id)
    {
        $a = Absence::mine()->findOrFail($id);
        // Suppression possible si non approuvé
        if ($a->status !== 'approuvé') {
            $a->delete();
            session()->flash('success', 'Demande supprimée.');
        } else {
            session()->flash('error', 'Impossible de supprimer une demande approuvée.');
        }
    }

    // Actions manager (optionnel: protège via Policy/Middleware dans tes routes)
    public function approve($id)
    {
        $a = Absence::findOrFail($id);
        // Exemple simple: tous peuvent approuver (à adapter avec Gate)
        $a->update([
            'status' => 'approuvé',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'justification' => $this->justification ?: null,
        ]);
        session()->flash('success', 'Demande approuvée.');
        $this->justification = null;
    }

    public function reject($id)
    {
        $this->validateOnly('justification', [
            'justification' => ['required', 'string', 'min:5', 'max:8000']
        ]);
        $a = Absence::findOrFail($id);
        $a->update([
            'status' => 'rejeté',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'justification' => $this->justification,
        ]);
        session()->flash('success', 'Demande rejetée avec justification.');
        $this->justification = null;
    }

    public function resetForm()
    {
        $this->type = 'congé_payé';
        $this->start_datetime = null;
        $this->end_datetime = null;
        $this->reason = null;
        $this->justification = null;
    }

    public function render()
    {
        $items = Absence::mine()
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $s = "%{$this->search}%";
                $q->where(function ($qq) use ($s) {
                    $qq->where('type', 'like', $s)
                        ->orWhere('reason', 'like', $s)
                        ->orWhere('justification', 'like', $s);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.absences.manager', compact('items'));
    }
}
