<?php

namespace App\Livewire\Settings;

use App\Models\ConformitySubmission;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodeItem;

class ReviewForm extends Component
{
    public ?string $submissionId = null;
    public ?string $notes = null;
    public bool $showConfirmApprove = false;
    public bool $showConfirmReject = false;

    // ❌ Plus besoin d’écouter 'open-review-modal'
    // #[On('open-review-modal')] public function openForSubmission(string $submissionId): void { ... }

    public function mount(?string $submissionId = null): void
    {
        $this->submissionId = $submissionId;
    }

    private function isFinal(ConformitySubmission $s): bool
    {
        return in_array($s->status, ['approuvé', 'rejeté'], true);
    }
    public function cancelConfirm(): void
    {
        // Ferme les bannières de confirmation
        $this->showConfirmApprove = false;
        $this->showConfirmReject  = false;

        // Nettoie les erreurs de validation éventuelles
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function normalizeType(string $raw): string
    {
        return match ($raw) {
            'file', 'documents', 'document' => 'documents',
            'texte', 'text'                => 'texte',
            'liste', 'list'                => 'liste',
            'checkbox', 'checks'           => 'checkbox',
            default                       => $raw,
        };
    }

    public function rules(): array
    {
        if (!$this->submissionId) return ['__noop' => 'nullable'];

        $s = ConformitySubmission::with('item')->findOrFail($this->submissionId);
        if ($s->isFinal()) return ['__noop' => 'nullable'];

        $type = $this->normalizeType($s->item->type);
        $rules = [];
        if ($type === 'texte') $rules['notes'] = 'nullable|string|max:1000';

        return $rules ?: ['__noop' => 'nullable'];
    }

    public function messages(): array
    {
        return [
            'notes.required' => 'Un commentaire est obligatoire.',
            'notes.min'      => 'Le commentaire doit contenir au moins :min caractères.',
            'notes.max'      => 'Le commentaire ne doit pas dépasser :max caractères.',
        ];
    }

    public function prepareApprove(): void
    {
        $this->showConfirmApprove = true;
        $this->showConfirmReject = false;
    }

    public function confirmApprove()
    {
        $s = ConformitySubmission::findOrFail($this->submissionId);
        if ($s->isFinal()) {
            $this->dispatch('notify', type: 'warning', message: 'Cette soumission a déjà été traitée.');
            return;
        }

        DB::transaction(function () use ($s) {
            // 1) Approuver la soumission
            $s->update([
                'status'         => 'approuvé',
                'reviewed_by'    => Auth::id(),
                'reviewed_at'    => now(),
                'reviewer_notes' => $this->notes,
            ]);

            // 2) Désactiver la période liée à cette soumission
            if ($s->periode_item_id) {
                PeriodeItem::where('id', $s->periode_item_id)
                    ->update([
                        'statut'         => '0',
                        'user_update_id' => Auth::id(),
                        'updated_at'     => now(),
                    ]);
            }
        });

        $this->dispatch('notify', type: 'success', message: 'Déclaration approuvée et période désactivée.');
    }

    public function prepareReject(): void
    {
        $this->validate([
            'notes' => 'required|string|min:10|max:1000'
        ], [
            'notes.required' => 'Un commentaire est obligatoire pour rejeter une soumission.',
            'notes.min'      => 'Le commentaire doit contenir au moins 10 caractères.',
        ]);

        $this->showConfirmReject = true;
        $this->showConfirmApprove = false;
    }

    public function confirmReject()
    {
        $this->validate([
            'notes' => 'required|string|min:10|max:1000'
        ]);

        $s = ConformitySubmission::findOrFail($this->submissionId);
        if ($s->isFinal()) {
            $this->dispatch('notify', type: 'warning', message: 'Cette soumission a déjà été traitée.');
            // return redirect()->route('conformite.review', $s->id);
        }

        $s->update([
            'status'         => 'rejeté',
            'reviewed_by'    => Auth::id(),
            'reviewed_at'    => now(),
            'reviewer_notes' => $this->notes,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Déclaration rejetée. L\'entreprise peut soumettre une correction.');
        // return redirect()->route('conformite.review', $s->id);
    }

    public function downloadAllDocuments(): void
    {
        $s = ConformitySubmission::with('answers')->findOrFail($this->submissionId);
        $docs = $s->answers()->where('kind', 'documents')->whereNotNull('file_path')->get();

        if ($docs->isEmpty()) {
            session()->flash('info', 'Aucun document à télécharger.');
            return;
        }
        session()->flash('info', $docs->count() . ' document(s) disponible(s). Utilisez les liens individuels.');
    }

    public function render()
    {
        if (!$this->submissionId) {
            return view('livewire.settings.review-form', ['submission' => null]);
        }

        $submission = ConformitySubmission::with([
            'item:id,nom_item,type,description',
            'periodeItem:id,item_id,debut_periode,fin_periode',
            'submittedBy:id,nom,prenom,email',
            'reviewedBy:id,nom,prenom',
            'answers' => fn($q) => $q->orderBy('position')->orderBy('id'),
        ])->findOrFail($this->submissionId);

        return view('livewire.settings.review-form', compact('submission'));
    }
}
