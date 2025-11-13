<?php

namespace App\Livewire\Settings;

use App\Models\ConformitySubmission;
use App\Models\PeriodeItem;
use App\Services\ValidationIAService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
    public bool $showConfirmReject  = false;
    public string $notes = '';

    // États UI
    public string $errorMessage   = '';
    public string $successMessage = '';

    // Validation
    protected $rules = [
        'notes' => 'nullable|string|max:2000',
    ];

    /**
     * Monté en pleine page : @livewire('settings.review-wizard', ['submissionId' => $submission->id])
     */
    public function mount(string $submissionId): void
    {
        $this->traceId      = (string) Str::uuid();
        $this->submissionId = $submissionId;

        Log::info('[ReviewWizard] mount()', [
            'trace_id'      => $this->traceId,
            'submission_id' => $submissionId,
        ]);

        $this->loadSubmission();
    }

    /**
     * Chargement de la soumission + relations
     */
    private function loadSubmission(): void
    {
        $this->submission = ConformitySubmission::with([
            'item:id,nom_item,type,description',
            'periodeItem:id,item_id,debut_periode,fin_periode',
            'submittedBy:id,nom,prenom,email',
            'reviewedBy:id,nom,prenom',
            'answers' => fn ($q) => $q->orderBy('position')->orderBy('id'),
        ])->findOrFail($this->submissionId);
    }
    /**
     * Demander une analyse IA de la soumission
     */
    public function requestAiAnalysis(): void
    {
        $this->loadingAiAnalysis = true;
        $this->showAiAnalysis    = false;
        $this->errorMessage      = '';

        try {
            $service = app(ValidationIAService::class);

            $result = $service->analyserSoumission($this->submission);

            if ($result['success']) {
                $this->aiAnalysis     = $result['analyse'];
                $this->showAiAnalysis = true;

                $this->dispatch('notify', type: 'success', message: "Analyse terminée !");
            } else {
                $this->errorMessage = "Erreur lors de l'analyse IA : " . ($result['error'] ?? 'Erreur inconnue');
                $this->dispatch('notify', type: 'error', message: $this->errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('[ReviewWizard] Erreur analyse IA', [
                'trace_id'      => $this->traceId,
                'submission_id' => $this->submissionId,
                'error'         => $e->getMessage(),
            ]);

            $this->errorMessage = "Impossible d'analyser la soumission avec l'IA.";
            $this->dispatch('notify', type: 'error', message: $this->errorMessage);
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
            $this->dispatch('notify', type: 'error', message: $this->errorMessage);
            return;
        }

        try {
            $service = app(ValidationIAService::class);
            $comment = $service->genererCommentaireApprobation($this->submission, $this->aiAnalysis);

            $this->notes          = $comment;
            $this->successMessage = "Commentaire généré par l'IA. Vous pouvez le modifier si nécessaire.";
            $this->dispatch('notify', type: 'success', message: $this->successMessage);
        } catch (\Exception $e) {
            Log::error('[ReviewWizard] Erreur génération commentaire approbation', [
                'trace_id' => $this->traceId,
                'error'    => $e->getMessage(),
            ]);

            $this->errorMessage = "Impossible de générer le commentaire.";
            $this->dispatch('notify', type: 'error', message: $this->errorMessage);
        }
    }

    /**
     * Générer un commentaire de rejet avec l'IA
     */
    public function generateRejectionComment(): void
    {
        if (empty($this->aiAnalysis)) {
            $this->errorMessage = "Veuillez d'abord demander une analyse IA.";
            $this->dispatch('notify', type: 'error', message: $this->errorMessage);
            return;
        }

        try {
            $service = app(ValidationIAService::class);
            $comment = $service->genererCommentaireRejet($this->submission, $this->aiAnalysis, $this->notes);

            $this->notes          = $comment;
            $this->successMessage = "Commentaire de rejet généré par l'IA. Vous pouvez le modifier.";
            $this->dispatch('notify', type: 'success', message: $this->successMessage);
        } catch (\Exception $e) {
            Log::error('[ReviewWizard] Erreur génération commentaire rejet', [
                'trace_id' => $this->traceId,
                'error'    => $e->getMessage(),
            ]);

            $this->errorMessage = "Impossible de générer le commentaire.";
            $this->dispatch('notify', type: 'error', message: $this->errorMessage);
        }
    }

    /** Préparer l'approbation */
    public function prepareApprove(): void
    {
        $this->showConfirmApprove = true;
        $this->showConfirmReject  = false;
        $this->errorMessage       = '';
    }

    /** Préparer le rejet */
    public function prepareReject(): void
    {
        $this->showConfirmReject  = true;
        $this->showConfirmApprove = false;
        $this->errorMessage       = '';
    }

    /** Annuler la confirmation (quel que soit le type) */
    public function cancelConfirm(): void
    {
        $this->showConfirmApprove = false;
        $this->showConfirmReject  = false;
    }

   /** Confirmer l'approbation */
   public function confirmApprove(): void
   {
       $this->validate();

       DB::beginTransaction();

       try {
           // 1) Mise à jour de la soumission
           $this->submission->update([
               'status'         => 'approuvé',
               'reviewed_at'    => now(),
               'reviewed_by'    => auth()->id(),
               'reviewer_notes' => $this->notes ?: 'Approuvé',
           ]);

           // 2) Désactiver la période liée (pour pouvoir en recréer une nouvelle)
           //    -> attention : la colonne est bien "periode_id" dans ConformitySubmission
           $periodeId = $this->submission->periode_id ?? $this->submission->periodeItem?->id;

           if ($periodeId) {
               PeriodeItem::where('id', $periodeId)->update([
                   'statut'         => '0',
                   'user_update_id' => Auth::id(),
                   'updated_at'     => now(),
               ]);
           }

           DB::commit();

           Log::info('[ReviewWizard] Soumission approuvée', [
               'trace_id'      => $this->traceId,
               'submission_id' => $this->submissionId,
           ]);

           // 3) Rafraîchir les données locales + état UI
           $this->loadSubmission();        // 🔁 recharge $this->submission avec le nouveau status
           $this->resetErrorBag();
           $this->resetValidation();

           $this->showConfirmApprove = false;
           $this->showConfirmReject  = false;
           $this->notes              = '';
           $this->aiAnalysis         = [];

           $this->successMessage = "Soumission approuvée avec succès !";

           // Event pour un éventuel board parent
           $this->dispatch('settings-reviewed');
           $this->dispatch('notify', type: 'success', message: $this->successMessage);
       } catch (\Exception $e) {
           DB::rollBack();

           Log::error('[ReviewWizard] Erreur approbation', [
               'trace_id' => $this->traceId,
               'error'    => $e->getMessage(),
           ]);

           $this->errorMessage       = "Erreur lors de l'approbation : " . $e->getMessage();
           $this->showConfirmApprove = false;
       }
   }

     /** Confirmer le rejet */
     public function confirmReject(): void
     {
         $this->validate([
             'notes' => 'required|string|min:10|max:2000',
         ]);
 
         DB::beginTransaction();
 
         try {
             $this->submission->update([
                 'status'         => 'rejeté',
                 'reviewed_at'    => now(),
                 'reviewed_by'    => auth()->id(),
                 'reviewer_notes' => $this->notes,
             ]);
 
             DB::commit();
 
             Log::info('[ReviewWizard] Soumission rejetée', [
                 'trace_id'      => $this->traceId,
                 'submission_id' => $this->submissionId,
             ]);
 
             // 🔁 Rafraîchir les données locales + UI
             $this->loadSubmission();
             $this->resetErrorBag();
             $this->resetValidation();
 
             $this->showConfirmApprove = false;
             $this->showConfirmReject  = false;
             $this->notes              = '';
             $this->aiAnalysis         = [];
             $this->successMessage     = "Soumission rejetée. L'entreprise sera notifiée.";
 
             $this->dispatch('settings-reviewed');
             $this->dispatch('notify', type: 'success', message: $this->successMessage);
         } catch (\Exception $e) {
             DB::rollBack();
 
             Log::error('[ReviewWizard] Erreur rejet', [
                 'trace_id' => $this->traceId,
                 'error'    => $e->getMessage(),
             ]);
 
             $this->errorMessage      = "Erreur lors du rejet : " . $e->getMessage();
             $this->showConfirmReject = false;
         }
     }
    public function render()
    {
        
       // On passe toujours la propriété $this->submission à la vue
       return view('livewire.settings.review-wizard', [
        'submission' => $this->submission,
    ]);
    }
}
