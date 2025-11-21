<?php

namespace App\Services;

use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;
use App\Models\Entreprise;
use App\Models\User;
use App\Models\Item;
use App\Mail\PeriodeCreatedMail;
use App\Mail\PeriodeModifiedMail;
use App\Mail\PeriodeCanceledMail;
use App\Mail\SubmissionApprovedMail;
use App\Mail\SubmissionRejectedMail;
use App\Mail\NewSubmissionMail;
use App\Mail\RappelEcheanceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailConformiteService
{
    /**
     * Envoyer un email de création de période à l'entreprise
     */
    public function envoyerEmailPeriodeCreated(PeriodeItem $periode): void
    {
        try {
            $item = Item::find($periode->item_id);
            $entreprise = Entreprise::find($periode->entreprise_id);

            if (!$entreprise) {
                Log::warning("Impossible d'envoyer l'email : entreprise sans email", [
                    'periode_id' => $periode->id,
                    'entreprise_id' => $periode->entreprise_id
                ]);
                return;
            }

            
            // Envoyer aux utilisateurs de l'entreprise

            $users = User::with('role')
                ->where('entreprise_id', $entreprise->id)
                ->whereNotNull('email_professionnel')
                ->whereHas('role', function ($q) {
                    $q->whereIn('nom', ['SuiviAudit', 'Gerant']);
                })
                ->get();


            foreach ($users as $user) {
                Mail::to($user->email_professionnel)
                    ->queue(new PeriodeCreatedMail($periode, $item, $entreprise, $user));
            }

            Log::info("Email de nouvelle période envoyé", [
                'periode_id' => $periode->id,
                'item_id' => $item->id,
                'entreprise_id' => $entreprise->id,
                'recipients' => $users->count()
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email de période créée", [
                'periode_id' => $periode->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un email de modification de période
     */
    public function envoyerEmailPeriodeModified(PeriodeItem $periode, array $changes = []): void
    {
        try {
            $item = Item::find($periode->item_id);
            $entreprise = Entreprise::find($periode->entreprise_id);

            if (!$entreprise) {
                return;
            }

            $users = User::with('role')
                ->where('entreprise_id', $entreprise->id)
                ->whereNotNull('email_professionnel')
                ->whereHas('role', function ($q) {
                    $q->whereIn('nom', ['SuiviAudit', 'Gerant']);
                })
                ->get();


            foreach ($users as $user) {
                Mail::to($user->email_professionnel)
                    ->queue(new PeriodeModifiedMail($periode, $item, $entreprise, $user, $changes));
            }

            Log::info("Email de modification de période envoyé", [
                'periode_id' => $periode->id,
                'changes' => $changes
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email de période modifiée", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un email d'annulation de période
     */
    public function envoyerEmailPeriodeCanceled(PeriodeItem $periode, string $raison = ''): void
    {
        try {
            $item = Item::find($periode->item_id);
            $entreprise = Entreprise::find($periode->entreprise_id);

            if (!$entreprise) {
                return;
            }

            $users = User::with('role')
                ->where('entreprise_id', $entreprise->id)
                ->whereNotNull('email_professionnel')
                ->whereHas('role', function ($q) {
                    $q->whereIn('nom', ['SuiviAudit', 'Gerant']);
                })
                ->get();


            foreach ($users as $user) {
                Mail::to($user->email_professionnel)
                    ->queue(new PeriodeCanceledMail($periode, $item, $entreprise, $user, $raison));
            }

            Log::info("Email d'annulation de période envoyé", [
                'periode_id' => $periode->id,
                'raison' => $raison
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email d'annulation", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un email d'approbation de soumission
     */
    public function envoyerEmailSubmissionApproved(ConformitySubmission $submission): void
    {
        try {
            $item = Item::find($submission->item_id);
            $entreprise = Entreprise::find($submission->entreprise_id);
            $submitter = User::find($submission->submitted_by);
            $reviewer = User::find($submission->reviewed_by);

            if (!$submitter || !$submitter->email_professionnel) {
                Log::warning("Impossible d'envoyer l'email : soumetteur sans email", [
                    'submission_id' => $submission->id
                ]);
                return;
            }

            Mail::to($submitter->email_professionnel)
                ->queue(new SubmissionApprovedMail($submission, $item, $entreprise, $submitter, $reviewer));

            // Copie aux responsables de l'entreprise (optionnel)
            $managers = User::where('entreprise_id', $entreprise->id)
                ->where('id', '!=', $submitter->id)
                ->whereHas('role', function ($q) {
                    $q->where('nom', 'Gerant')->orWhere('superAdmin', true);
                })
                ->whereNotNull('email_professionnel')
                ->get();

            foreach ($managers as $manager) {
                Mail::to($manager->email_professionnel)
                    ->queue(new SubmissionApprovedMail($submission, $item, $entreprise, $manager, $reviewer));
            }

            Log::info("Email d'approbation envoyé", [
                'submission_id' => $submission->id,
                'submitter_email' => $submitter->email_professionnel
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email d'approbation", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un email de rejet de soumission
     */
    public function envoyerEmailSubmissionRejected(ConformitySubmission $submission): void
    {
        try {
            $item = Item::find($submission->item_id);
            $entreprise = Entreprise::find($submission->entreprise_id);
            $submitter = User::find($submission->submitted_by);
            $reviewer = User::find($submission->reviewed_by);

            if (!$submitter || !$submitter->email_professionnel) {
                return;
            }

            Mail::to($submitter->email_professionnel)
                ->queue(new SubmissionRejectedMail($submission, $item, $entreprise, $submitter, $reviewer));

            Log::info("Email de rejet envoyé", [
                'submission_id' => $submission->id,
                'submitter_email' => $submitter->email_professionnel
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email de rejet", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un email de nouvelle soumission aux admins
     */
    public function envoyerEmailNewSubmission(ConformitySubmission $submission): void
    {
        try {
            $item = Item::find($submission->item_id);
            $entreprise = Entreprise::find($submission->entreprise_id);
            $submitter = User::find($submission->submitted_by);

            // Récupérer tous les validateurs (rôle ValideAudit)
            $admins = User::whereHas('role', function ($q) {
                $q->where('nom', 'ValideAudit');
            })
                ->whereNotNull('email_professionnel')
                ->get();

            if ($admins->isEmpty()) {
                Log::warning("Aucun admin trouvé pour recevoir l'email de nouvelle soumission", [
                    'submission_id' => $submission->id
                ]);
                return;
            }

            foreach ($admins as $admin) {
                Mail::to($admin->email_professionnel)
                    ->queue(new NewSubmissionMail($submission, $item, $entreprise, $submitter, $admin));
            }

            Log::info("Email de nouvelle soumission envoyé aux admins", [
                'submission_id' => $submission->id,
                'admins_count' => $admins->count()
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de l'email de nouvelle soumission", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un rappel d'échéance
     */
    public function envoyerRappelEcheance(
        PeriodeItem $periode,
        int $joursRestants,
        string $urgence = 'normal'
    ): void {
        try {
            $item = Item::find($periode->item_id);
            $entreprise = Entreprise::find($periode->entreprise_id);

            if (!$entreprise) {
                return;
            }

            // Vérifier s'il existe déjà une soumission approuvée
            $hasApprovedSubmission = ConformitySubmission::where('item_id', $periode->item_id)
                ->where('entreprise_id', $periode->entreprise_id)
                ->where('periode_item_id', $periode->id)
                ->where('status', 'approuvé')
                ->exists();

            // Ne pas envoyer de rappel si déjà approuvé
            if ($hasApprovedSubmission) {
                Log::info("Rappel non envoyé : soumission déjà approuvée", [
                    'periode_id' => $periode->id
                ]);
                return;
            }


            $users = User::with('role')
                ->where('entreprise_id', $entreprise->id)
                ->whereNotNull('email_professionnel')
                ->whereHas('role', function ($q) {
                    $q->whereIn('nom', ['SuiviAudit', 'Gerant']);
                })
                ->get();

            foreach ($users as $user) {
                Mail::to($user->email_professionnel)
                    ->queue(new RappelEcheanceMail(
                        $periode,
                        $item,
                        $entreprise,
                        $user,
                        $joursRestants,
                        $urgence
                    ));
            }

            Log::info("Rappel d'échéance envoyé", [
                'periode_id' => $periode->id,
                'jours_restants' => $joursRestants,
                'urgence' => $urgence,
                'recipients' => $users->count()
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi du rappel d'échéance", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer les rappels automatiques selon les échéances
     */
    public function verifierEtEnvoyerRappels(): int
    {
        $rappelsEnvoyes = 0;
        $now = now();

        // Récupérer toutes les périodes actives
        $periodes = PeriodeItem::where('statut', '1')
            ->whereDate('fin_periode', '>=', $now)
            ->get();

        foreach ($periodes as $periode) {
            $joursRestants = $now->diffInDays($periode->fin_periode, false);
            $heuresRestantes = $now->diffInHours($periode->fin_periode, false);

            // Vérifier si une soumission approuvée existe
            $hasApprovedSubmission = ConformitySubmission::where('item_id', $periode->item_id)
                ->where('entreprise_id', $periode->entreprise_id)
                ->where('periode_item_id', $periode->id)
                ->where('status', 'approuvé')
                ->exists();

            if ($hasApprovedSubmission) {
                continue;
            }

            // Rappel à 7 jours
            if ($joursRestants == 7) {
                $this->envoyerRappelEcheance($periode, 7, 'normal');
                $rappelsEnvoyes++;
            }
            // Rappel à 3 jours
            elseif ($joursRestants == 3) {
                $this->envoyerRappelEcheance($periode, 3, 'important');
                $rappelsEnvoyes++;
            }
            // Rappel à 1 jour
            elseif ($joursRestants == 1) {
                $this->envoyerRappelEcheance($periode, 1, 'urgent');
                $rappelsEnvoyes++;
            }
            // Rappel à 1 heure
            elseif ($joursRestants == 0 && $heuresRestantes == 1) {
                $this->envoyerRappelEcheance($periode, 0, 'critique');
                $rappelsEnvoyes++;
            }
        }

        Log::info("Vérification des rappels terminée", [
            'rappels_envoyes' => $rappelsEnvoyes
        ]);

        return $rappelsEnvoyes;
    }
}
