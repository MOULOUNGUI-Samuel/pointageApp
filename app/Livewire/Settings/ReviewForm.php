<?php

namespace App\Livewire\Settings;

use App\Models\ConformitySubmission;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReviewForm extends Component
{
    public string $submissionId;
    public ?string $notes = null;

    public function mount(string $submissionId): void
    {
        $this->submissionId = $submissionId;
    }

    public function approve(): void
    {
        $s = ConformitySubmission::findOrFail($this->submissionId);
        // $p = PeriodeItem::where('item_id', $this->item->id)
        if ($s->isFinal()) return;

        $s->update([
            'status'         => 'approuvé',
            'reviewed_by'    => Auth::id(),
            'reviewed_at'    => now(),
            'reviewer_notes' => $this->notes,
        ]);

        session()->flash('success', 'Déclaration approuvée.');
        $this->dispatch('settings-reviewed', id: $s->id);
        
        // ⬇️ Fermer la modale correspondante
        $this->dispatch('close-review-modal', submissionId: $s->id);
        
        $this->reset('notes');
        // $this->dispatch('wizard-config-reload');
    }

    public function reject(): void
    {
        $this->validate(['notes' => 'required|string|min:3']);

        $s = ConformitySubmission::findOrFail($this->submissionId);
        if ($s->isFinal()) return;

        $s->update([
            'status'         => 'rejeté',
            'reviewed_by'    => Auth::id(),
            'reviewed_at'    => now(),
            'reviewer_notes' => $this->notes,
        ]);

        session()->flash('success', 'Déclaration rejetée.');
        $this->dispatch('settings-reviewed', id: $s->id);
        // $this->dispatch('wizard-config-reload');

        // ⬇️ Fermer la modale correspondante
        $this->dispatch('close-review-modal', submissionId: $s->id);
    }


    public function render()
    {
        // ⚠️ eager load complet + ordre des réponses
        $submission = ConformitySubmission::with([
            'item:id,nom_item,type,description',
            'periode:id,item_id,debut_periode,fin_periode',
            'submitter:id,nom,prenom',
            'reviewer:id,nom,prenom',
            'answers' => fn($q) => $q->orderBy('position')->orderBy('id'),
        ])->findOrFail($this->submissionId);

        return view('livewire.Settings.review-form', compact('submission'));
    }
}
