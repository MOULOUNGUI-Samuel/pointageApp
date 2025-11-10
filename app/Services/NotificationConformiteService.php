<?php

namespace App\Services;

use App\Models\NotificationConformite;
use App\Models\Entreprise;
use App\Models\User;
use App\Models\Item;
use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;
use App\Events\NotificationConformiteCreated;
use Illuminate\Support\Collection;

class NotificationConformiteService
{
    /**
     * Créer une notification pour une nouvelle période
     */
    public function notifierNouvellePeriode(
        PeriodeItem $periode,
        Entreprise $entreprise
    ): NotificationConformite {
        $item = $periode->item;
        $categorie = $item->categorieDomaine;
        $domaine = $categorie->domaine;

        $notification = NotificationConformite::create([
            'entreprise_id' => $entreprise->id,
            'user_id' => null, // Tous les users de l'entreprise
            'type' => NotificationConformite::TYPE_NOUVELLE_PERIODE,
            'titre' => '📋 Nouveau document à fournir',
            'message' => sprintf(
                'Une nouvelle période a été définie pour "%s" (Domaine: %s). Merci de compléter les informations avant le %s.',
                $item->nom_item,
                $domaine->nom_domaine,
                $periode->fin_periode->format('d/m/Y')
            ),
            'item_id' => $item->id,
            'periode_item_id' => $periode->id,
            'domaine_id' => $domaine->id,
            'categorie_domaine_id' => $categorie->id,
            'priorite' => NotificationConformite::PRIORITE_NORMALE,
            'metadata' => [
                'date_debut' => $periode->debut_periode->format('Y-m-d'),
                'date_fin' => $periode->fin_periode->format('Y-m-d'),
                'jours_restants' => now()->diffInDays($periode->fin_periode, false),
                'type_item' => $item->type,
            ],
        ]);

        event(new NotificationConformiteCreated($notification));

        return $notification;
    }

    /**
     * Créer une notification de validation
     */
    public function notifierValidation(
        ConformitySubmission $soumission
    ): NotificationConformite {
        $item = $soumission->item;
        $entreprise = $soumission->entreprise;
        $validateur = $soumission->reviewedBy;

        $notification = NotificationConformite::create([
            'entreprise_id' => $entreprise->id,
            'user_id' => $soumission->submitted_by,
            'type' => NotificationConformite::TYPE_VALIDATION,
            'titre' => '✅ Document approuvé',
            'message' => sprintf(
                'Votre soumission pour "%s" a été approuvée par %s.',
                $item->nom_item,
                $validateur ? $validateur->nom . ' ' . $validateur->prenom : 'l\'équipe de validation'
            ),
            'item_id' => $item->id,
            'periode_item_id' => $soumission->periode_item_id,
            'soumission_id' => $soumission->id,
            'priorite' => NotificationConformite::PRIORITE_BASSE,
            'metadata' => [
                'validated_at' => $soumission->reviewed_at?->format('Y-m-d H:i:s'),
                'validator_name' => $validateur?->nom . ' ' . $validateur?->prenom,
                'notes' => $soumission->reviewer_notes,
            ],
        ]);

        event(new NotificationConformiteCreated($notification));

        return $notification;
    }

    /**
     * Créer une notification de refus
     */
    public function notifierRefus(
        ConformitySubmission $soumission,
        string $commentaire
    ): NotificationConformite {
        $item = $soumission->item;
        $entreprise = $soumission->entreprise;
        $validateur = $soumission->reviewedBy;

        $notification = NotificationConformite::create([
            'entreprise_id' => $entreprise->id,
            'user_id' => $soumission->submitted_by,
            'type' => NotificationConformite::TYPE_REFUS,
            'titre' => '❌ Document à corriger',
            'message' => sprintf(
                'Votre soumission pour "%s" nécessite des corrections. Raison: %s',
                $item->nom_item,
                $commentaire
            ),
            'item_id' => $item->id,
            'periode_item_id' => $soumission->periode_item_id,
            'soumission_id' => $soumission->id,
            'priorite' => NotificationConformite::PRIORITE_HAUTE,
            'metadata' => [
                'rejected_at' => $soumission->reviewed_at?->format('Y-m-d H:i:s'),
                'validator_name' => $validateur?->nom . ' ' . $validateur?->prenom,
                'commentaire' => $commentaire,
            ],
        ]);

        event(new NotificationConformiteCreated($notification));

        return $notification;
    }

    /**
     * Créer une notification de rappel d'échéance
     */
    public function notifierRappelEcheance(
        PeriodeItem $periode,
        Entreprise $entreprise,
        int $joursRestants
    ): NotificationConformite {
        $item = $periode->item;
        
        // Déterminer la priorité selon les jours restants
        $priorite = match(true) {
            $joursRestants <= 1 => NotificationConformite::PRIORITE_URGENTE,
            $joursRestants <= 3 => NotificationConformite::PRIORITE_HAUTE,
            $joursRestants <= 7 => NotificationConformite::PRIORITE_NORMALE,
            default => NotificationConformite::PRIORITE_BASSE,
        };

        $titre = match(true) {
            $joursRestants === 1 => '⚠️ Dernière chance',
            $joursRestants <= 3 => '⚠️ Échéance imminente',
            default => '⚠️ Échéance proche',
        };

        $notification = NotificationConformite::create([
            'entreprise_id' => $entreprise->id,
            'user_id' => null,
            'type' => NotificationConformite::TYPE_RAPPEL_ECHEANCE,
            'titre' => $titre,
            'message' => sprintf(
                'Il vous reste %d jour%s pour compléter "%s". Date limite: %s',
                $joursRestants,
                $joursRestants > 1 ? 's' : '',
                $item->nom_item,
                $periode->fin_periode->format('d/m/Y')
            ),
            'item_id' => $item->id,
            'periode_item_id' => $periode->id,
            'priorite' => $priorite,
            'metadata' => [
                'jours_restants' => $joursRestants,
                'date_fin' => $periode->fin_periode->format('Y-m-d'),
            ],
        ]);

        event(new NotificationConformiteCreated($notification));

        return $notification;
    }

    /**
     * Créer une notification de période expirée
     */
    public function notifierPeriodeExpiree(
        PeriodeItem $periode,
        Entreprise $entreprise
    ): NotificationConformite {
        $item = $periode->item;

        $notification = NotificationConformite::create([
            'entreprise_id' => $entreprise->id,
            'user_id' => null,
            'type' => NotificationConformite::TYPE_PERIODE_EXPIREE,
            'titre' => '⏰ Période expirée',
            'message' => sprintf(
                'La période pour "%s" a expiré le %s. Veuillez contacter l\'administration.',
                $item->nom_item,
                $periode->fin_periode->format('d/m/Y')
            ),
            'item_id' => $item->id,
            'periode_item_id' => $periode->id,
            'priorite' => NotificationConformite::PRIORITE_URGENTE,
            'metadata' => [
                'date_fin' => $periode->fin_periode->format('Y-m-d'),
                'jours_depuis_expiration' => now()->diffInDays($periode->fin_periode),
            ],
        ]);

        event(new NotificationConformiteCreated($notification));

        return $notification;
    }

    /**
     * Créer une notification de nouvelle soumission (pour admin)
     */
    public function notifierNouvelleSoumission(
        ConformitySubmission $soumission
    ): Collection {
        $item = $soumission->item;
        $entreprise = $soumission->entreprise;
        $submitter = $soumission->submittedBy;

        // Récupérer tous les admins avec le rôle ValideAudit
        $admins = User::whereHas('role', function ($query) {
            $query->where('nom', 'ValideAudit');
        })->get();

        $notifications = collect();

        foreach ($admins as $admin) {
            $notification = NotificationConformite::create([
                'entreprise_id' => $entreprise->id,
                'user_id' => $admin->id,
                'type' => NotificationConformite::TYPE_NOUVELLE_SOUMISSION,
                'titre' => '📩 Nouvelle soumission à valider',
                'message' => sprintf(
                    '%s a soumis "%s" pour validation.',
                    $entreprise->nom_entreprise,
                    $item->nom_item
                ),
                'item_id' => $item->id,
                'periode_item_id' => $soumission->periode_item_id,
                'soumission_id' => $soumission->id,
                'priorite' => NotificationConformite::PRIORITE_NORMALE,
                'metadata' => [
                    'submitted_at' => $soumission->submitted_at?->format('Y-m-d H:i:s'),
                    'submitter_name' => $submitter->nom . ' ' . $submitter->prenom,
                    'entreprise_nom' => $entreprise->nom_entreprise,
                ],
            ]);

            event(new NotificationConformiteCreated($notification));
            $notifications->push($notification);
        }

        return $notifications;
    }

    /**
     * Créer une notification de resoumission (pour admin)
     */
    public function notifierResoumission(
        ConformitySubmission $soumission
    ): Collection {
        $item = $soumission->item;
        $entreprise = $soumission->entreprise;

        // Récupérer tous les admins avec le rôle ValideAudit
        $admins = User::whereHas('role', function ($query) {
            $query->where('nom', 'ValideAudit');
        })->get();

        $notifications = collect();

        foreach ($admins as $admin) {
            $notification = NotificationConformite::create([
                'entreprise_id' => $entreprise->id,
                'user_id' => $admin->id,
                'type' => NotificationConformite::TYPE_RESOUMISSION,
                'titre' => '🔄 Correction reçue',
                'message' => sprintf(
                    '%s a resoumis "%s" après correction.',
                    $entreprise->nom_entreprise,
                    $item->nom_item
                ),
                'item_id' => $item->id,
                'periode_item_id' => $soumission->periode_item_id,
                'soumission_id' => $soumission->id,
                'priorite' => NotificationConformite::PRIORITE_HAUTE,
                'metadata' => [
                    'submitted_at' => $soumission->submitted_at?->format('Y-m-d H:i:s'),
                    'entreprise_nom' => $entreprise->nom_entreprise,
                ],
            ]);

            event(new NotificationConformiteCreated($notification));
            $notifications->push($notification);
        }

        return $notifications;
    }

    /**
     * Créer un rapport quotidien pour les admins
     */
    public function notifierRapportQuotidien(): Collection
    {
        // Compter les soumissions en attente
        $nbSoumissionsEnAttente = ConformitySubmission::where('status', 'soumis')->count();

        if ($nbSoumissionsEnAttente === 0) {
            return collect();
        }

        // Récupérer tous les admins avec le rôle ValideAudit
        $admins = User::whereHas('role', function ($query) {
            $query->where('nom', 'ValideAudit');
        })->get();

        $notifications = collect();

        foreach ($admins as $admin) {
            $notification = NotificationConformite::create([
                'entreprise_id' => $admins->first()->entreprise_id ?? null, // À adapter selon votre logique
                'user_id' => $admin->id,
                'type' => NotificationConformite::TYPE_RAPPORT_QUOTIDIEN,
                'titre' => '📊 Rapport quotidien',
                'message' => sprintf(
                    'Vous avez %d soumission%s en attente de validation.',
                    $nbSoumissionsEnAttente,
                    $nbSoumissionsEnAttente > 1 ? 's' : ''
                ),
                'priorite' => NotificationConformite::PRIORITE_NORMALE,
                'metadata' => [
                    'nb_soumissions' => $nbSoumissionsEnAttente,
                    'date_rapport' => now()->format('Y-m-d'),
                ],
            ]);

            event(new NotificationConformiteCreated($notification));
            $notifications->push($notification);
        }

        return $notifications;
    }

    /**
     * Marquer toutes les notifications d'un user comme lues
     */
    public function marquerToutesCommeLues(User $user): int
    {
        return NotificationConformite::where('user_id', $user->id)
            ->nonLues()
            ->update([
                'lue' => true,
                'lue_le' => now(),
            ]);
    }

    /**
     * Supprimer les anciennes notifications
     */
    public function supprimerAnciennesNotifications(int $joursGarde = 90): int
    {
        return NotificationConformite::where('created_at', '<', now()->subDays($joursGarde))
            ->where('lue', true)
            ->delete();
    }

    /**
     * Obtenir le nombre de notifications non lues pour un user
     */
    public function compterNonLues(User $user): int
    {
        return NotificationConformite::where('user_id', $user->id)
            ->nonLues()
            ->count();
    }

    /**
     * Obtenir le nombre de notifications non lues pour une entreprise
     */
    public function compterNonLuesEntreprise(Entreprise $entreprise): int
    {
        return NotificationConformite::where('entreprise_id', $entreprise->id)
            ->where(function ($query) use ($entreprise) {
                $query->whereNull('user_id')
                    ->orWhereIn('user_id', $entreprise->users()->pluck('id'));
            })
            ->nonLues()
            ->count();
    }
}