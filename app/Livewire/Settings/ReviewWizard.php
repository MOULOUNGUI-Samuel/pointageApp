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
use App\Services\EmailConformiteService;
use Carbon\Carbon;

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

    // Services
    protected EmailConformiteService $emailService;

    public function boot(EmailConformiteService $emailService): void
    {
        $this->emailService = $emailService;
    }

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
            'answers' => fn($q) => $q->orderBy('position')->orderBy('id'),
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

        try {
            $s = ConformitySubmission::findOrFail($this->submissionId);
            if ($s->isFinal()) {
                $this->dispatch('notify', type: 'warning', message: 'Cette soumission a déjà été traitée.');
                $this->errorMessage = "Cette soumission a déjà été traitée.";
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

            // 3) 📧 Envoyer l'email de validation
            try {
                $this->emailService->envoyerEmailSubmissionApproved($s);
            } catch (\Exception $e) {
                Log::error('Erreur envoi email approbation', ['error' => $e->getMessage()]);
            }

            $this->dispatch('notify', type: 'success', message: 'Déclaration approuvée et période désactivée.');
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
            $s = ConformitySubmission::findOrFail($this->submissionId);
            // 2) Réouvrir automatiquement une nouvelle période (7 jours)
            $nouvellePeriode = null;

            if ($s->periode_item_id) {
                $anciennePeriode = PeriodeItem::find($s->periode_item_id);

                if ($anciennePeriode) {
                    // Désactiver l'ancienne période
                    $anciennePeriode->update([
                        'statut'         => '0',
                        'user_update_id' => Auth::id(),
                    ]);

                    // Créer une nouvelle période de correction (7 jours)
                    $nouvellePeriode = PeriodeItem::create([
                        'item_id'        => $anciennePeriode->item_id,
                        'entreprise_id'  => $anciennePeriode->entreprise_id,
                        'debut_periode'  => Carbon::now(),
                        'fin_periode'    => $anciennePeriode->fin_periode->addDays(7), // 7 jours pour corriger
                        'statut'         => '1',
                        'user_add_id'    => Auth::id(),
                    ]);
                }
            }

            // 3) 📧 Envoyer l'email de refus
            try {
                $this->emailService->envoyerEmailSubmissionRejected($s);

                // Envoyer aussi un email pour la nouvelle période si créée
                if ($nouvellePeriode) {
                    $this->emailService->envoyerEmailPeriodeCreated($nouvellePeriode);
                }
            } catch (\Exception $e) {
                Log::error('Erreur envoi email rejet', ['error' => $e->getMessage()]);
            }

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
        if (!$this->submissionId) {
            return view('livewire.settings.review-wizard', ['submission' => null]);
        }

        $submission = ConformitySubmission::with([
            'item:id,nom_item,type,description',
            'periodeItem:id,item_id,debut_periode,fin_periode',
            'submittedBy:id,nom,prenom,email',
            'reviewedBy:id,nom,prenom',
            'answers' => fn($q) => $q->orderBy('position')->orderBy('id'),
        ])->findOrFail($this->submissionId);
        // On passe toujours la propriété $this->submission à la vue
        return view('livewire.settings.review-wizard', [
            'submission' => $submission,
        ]);
    }
}
