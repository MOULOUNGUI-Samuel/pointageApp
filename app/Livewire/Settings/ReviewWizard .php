<?php

namespace App\Livewire\Settings;

use App\Models\ConformitySubmission;
use App\Services\ValidationIAService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;

class ReviewWizard extends Component
{
    public string $traceId;

    // ID de la soumission
    public ?string $submissionId = null;

    // Données
    public ?ConformitySubmission $submission = null;

    // États IA
    public bool $showAiAnalysis = false;
    public bool $loadingAiAnalysis = false;
    public array $aiAnalysis = [];

    // États de décision
    public bool $showConfirmApprove = false;
    public bool $showConfirmReject = false;
    public string $notes = '';

    // États UI
    public string $errorMessage = '';
    public string $successMessage = '';

    protected $listeners = [
        'open-review-modal2' => 'initializeForReview'
    ];

    protected $rules = [
        'notes' => 'nullable|string|max:2000'
    ];

    public function mount(): void
    {
        $this->traceId = (string) Str::uuid();
        Log::info('[ReviewWizard] mount()', ['trace_id' => $this->traceId]);
    }

    #[On('open-review-modal2')]
    public function initializeForReview(string $submissionId): void
    {
        $this->reset([
            'notes',
            'showAiAnalysis',
            'aiAnalysis',
            'showConfirmApprove',
            'showConfirmReject',
            'errorMessage',
            'successMessage'
        ]);

        $this->submissionId = $submissionId;

        Log::info('[ReviewWizard] initializeForReview()', [
            'trace_id' => $this->traceId,
            'submission_id' => $submissionId
        ]);

        $this->loadSubmission();
    }

    private function loadSubmission(): void
    {
        $this->submission = ConformitySubmission::with([
            'item.CategorieDomaine.Domaine',
            'item.options',
            'entreprise',
            'periode',
            'submitter',
            'reviewer',
            'answers.itemOption'
        ])->findOrFail($this->submissionId);
    }

    /**
     * Demander une analyse IA de la soumission
     */
    public function requestAiAnalysis(): void
    {
        $this->loadingAiAnalysis = true;
        $this->showAiAnalysis = false;
        $this->errorMessage = '';

        try {
            $service = app(ValidationIAService::class);

            $result = $service->analyserSoumission($this->submission);

            if ($result['success']) {
                $this->aiAnalysis = $result['analyse'];
                $this->showAiAnalysis = true;
            } else {
                $this->errorMessage = "Erreur lors de l'analyse IA : " . ($result['error'] ?? 'Erreur inconnue');
            }

        } catch (\Exception $e) {
            Log::error('[ReviewWizard] Erreur analyse IA', [
                'trace_id' => $this->traceId,
                'submission_id' => $this->submissionId,
                'error' => $e->getMessage()
            ]);
            $this->errorMessage = "Impossible d'analyser la soumission avec l'IA.";
        } finally {
            $this->loadingAiAnalysis = false;
        }
    }

    /**
     * Générer un commentaire d'approbation avec l'IA
     */
    public function generateApprovalComment(): void
    {
        if (empty($this->aiAnalysis)) {
            $this->errorMessage = "Veuillez d'abord demander une analyse IA.";
            return;
        }

        try {
            $service = app(ValidationIAService::class);
            $comment = $service->genererCommentaireApprobation($this->submission, $this->aiAnalysis);
            
            $this->notes = $comment;
            $this->successMessage = "Commentaire généré par l'IA. Vous pouvez le modifier si nécessaire.";

        } catch (\Exception $e) {
            Log::error('[ReviewWizard] Erreur génération commentaire approbation', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage()
            ]);
            $this->errorMessage = "Impossible de générer le commentaire.";
        }
    }

    /**
     * Générer un commentaire de rejet avec l'IA
     */
    public function generateRejectionComment(): void
    {
        if (empty($this->aiAnalysis)) {
            $this->errorMessage = "Veuillez d'abord demander une analyse IA.";
            return;
        }

        try {
            $service = app(ValidationIAService::class);
            $comment = $service->genererCommentaireRejet($this->submission, $this->aiAnalysis, $this->notes);
            
            $this->notes = $comment;
            $this->successMessage = "Commentaire de rejet généré par l'IA. Vous pouvez le modifier.";

        } catch (\Exception $e) {
            Log::error('[ReviewWizard] Erreur génération commentaire rejet', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage()
            ]);
            $this->errorMessage = "Impossible de générer le commentaire.";
        }
    }

    /**
     * Préparer l'approbation
     */
    public function prepareApprove(): void
    {
        $this->showConfirmApprove = true;
        $this->showConfirmReject = false;
        $this->errorMessage = '';
    }

    /**
     * Préparer le rejet
     */
    public function prepareReject(): void
    {
        $this->showConfirmReject = true;
        $this->showConfirmApprove = false;
        $this->errorMessage = '';
    }

    /**
     * Annuler la confirmation
     */
    public function cancelConfirm(): void
    {
        $this->showConfirmApprove = false;
        $this->showConfirmReject = false;
    }

    /**
     * Confirmer l'approbation
     */
    public function confirmApprove(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->submission->update([
                'status' => 'approuvé',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
                'reviewer_notes' => $this->notes ?: 'Approuvé'
            ]);

            DB::commit();

            Log::info('[ReviewWizard] Soumission approuvée', [
                'trace_id' => $this->traceId,
                'submission_id' => $this->submissionId
            ]);

            // Émettre événement pour rafraîchir le board
            $this->dispatch('settings-reviewed');

            // Fermer la modale
            $this->dispatch('close-review-modal');

            session()->flash('success', 'Soumission approuvée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('[ReviewWizard] Erreur approbation', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage()
            ]);

            $this->errorMessage = "Erreur lors de l'approbation : " . $e->getMessage();
            $this->showConfirmApprove = false;
        }
    }

    /**
     * Confirmer le rejet
     */
    public function confirmReject(): void
    {
        $this->validate([
            'notes' => 'required|string|min:10|max:2000'
        ]);

        DB::beginTransaction();

        try {
            $this->submission->update([
                'status' => 'rejeté',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
                'reviewer_notes' => $this->notes
            ]);

            DB::commit();

            Log::info('[ReviewWizard] Soumission rejetée', [
                'trace_id' => $this->traceId,
                'submission_id' => $this->submissionId
            ]);

            // Émettre événement pour rafraîchir le board
            $this->dispatch('settings-reviewed');

            // Fermer la modale
            $this->dispatch('close-review-modal');

            session()->flash('success', 'Soumission rejetée. L\'entreprise sera notifiée.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('[ReviewWizard] Erreur rejet', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage()
            ]);

            $this->errorMessage = "Erreur lors du rejet : " . $e->getMessage();
            $this->showConfirmReject = false;
        }
    }

    public function render()
    {
        return view('livewire.settings.review-wizard');
    }
}