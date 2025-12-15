<?php

namespace App\Services;

use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Models\ContractHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractService
{
    /**
     * Créer un nouveau contrat
     */
    public function createContract(array $data, User $createdBy): Contract
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // Vérifier si l'utilisateur a déjà un contrat actif
            $activeContract = Contract::where('user_id', $data['user_id'])
                ->where('statut', ContractStatus::ACTIF->value)
                ->first();

            if ($activeContract) {
                throw new \Exception("L'utilisateur a déjà un contrat actif. Veuillez le terminer avant d'en créer un nouveau.");
            }

            // Créer le contrat
            $contract = Contract::create([
                'user_id' => $data['user_id'],
                'entreprise_id' => $data['entreprise_id'],
                'type_contrat' => $data['type_contrat'],
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'] ?? null,
                'salaire_base' => $data['salaire_base'] ?? null,
                'mode_paiement' => $data['mode_paiement'] ?? null,
                'avantages' => $data['avantages'] ?? null,
                'statut' => $data['statut'] ?? ContractStatus::ACTIF->value,
                'version' => 1,
                'notes' => $data['notes'] ?? null,
                'fichier_joint' => $data['fichier_joint'] ?? null,
                'created_by' => $createdBy->id,
                'updated_by' => $createdBy->id,
            ]);

            // Créer l'historique
            ContractHistory::logCreation($contract, $createdBy, $data['comment'] ?? null);

            return $contract->fresh();
        });
    }

    /**
     * Mettre à jour un contrat
     */
    public function updateContract(Contract $contract, array $data, User $updatedBy): Contract
    {
        return DB::transaction(function () use ($contract, $data, $updatedBy) {
            // Vérifier si le contrat est modifiable
            if (!$contract->estModifiable()) {
                throw new \Exception("Ce contrat ne peut plus être modifié.");
            }

            // Sauvegarder les données originales pour l'historique
            $originalData = $contract->getOriginal();

            // Mettre à jour le contrat
            $contract->update([
                'type_contrat' => $data['type_contrat'] ?? $contract->type_contrat,
                'date_debut' => $data['date_debut'] ?? $contract->date_debut,
                'date_fin' => $data['date_fin'] ?? $contract->date_fin,
                'salaire_base' => $data['salaire_base'] ?? $contract->salaire_base,
                'mode_paiement' => $data['mode_paiement'] ?? $contract->mode_paiement,
                'avantages' => $data['avantages'] ?? $contract->avantages,
                'statut' => $data['statut'] ?? $contract->statut,
                'notes' => $data['notes'] ?? $contract->notes,
                'fichier_joint' => $data['fichier_joint'] ?? $contract->fichier_joint,
                'updated_by' => $updatedBy->id,
            ]);

            // Créer l'historique de modification
            if ($contract->wasChanged()) {
                ContractHistory::logUpdate($contract, $originalData, $updatedBy, $data['comment'] ?? null);
            }

            return $contract->fresh();
        });
    }

    /**
     * Renouveler un contrat
     */
    public function renewContract(Contract $oldContract, array $data, User $renewedBy): Contract
    {
        return DB::transaction(function () use ($oldContract, $data, $renewedBy) {
            // Vérifier si le contrat peut être renouvelé
            if (!$oldContract->estRenouvelable()) {
                throw new \Exception("Ce contrat ne peut pas être renouvelé pour le moment.");
            }

            // Terminer l'ancien contrat s'il est encore actif
            if ($oldContract->statut === ContractStatus::ACTIF->value) {
                $oldContract->marquerCommeTermine();
                ContractHistory::logStatusChange($oldContract, 'terminated', $renewedBy, 'Contrat terminé pour renouvellement');
            }

            // Créer le nouveau contrat
            $newVersion = $oldContract->version + 1;

            $newContract = Contract::create([
                'user_id' => $oldContract->user_id,
                'entreprise_id' => $oldContract->entreprise_id,
                'type_contrat' => $data['type_contrat'] ?? $oldContract->type_contrat,
                'date_debut' => $data['date_debut'] ?? now(),
                'date_fin' => $data['date_fin'] ?? null,
                'salaire_base' => $data['salaire_base'] ?? $oldContract->salaire_base,
                'mode_paiement' => $data['mode_paiement'] ?? $oldContract->mode_paiement,
                'avantages' => $data['avantages'] ?? $oldContract->avantages,
                'statut' => ContractStatus::ACTIF->value,
                'version' => $newVersion,
                'parent_contract_id' => $oldContract->id,
                'notes' => $data['notes'] ?? null,
                'fichier_joint' => $data['fichier_joint'] ?? null,
                'created_by' => $renewedBy->id,
                'updated_by' => $renewedBy->id,
            ]);

            // Créer l'historique de renouvellement
            ContractHistory::logRenewal($newContract, $oldContract, $renewedBy, $data['comment'] ?? null);

            return $newContract->fresh();
        });
    }

    /**
     * Suspendre un contrat
     */
    public function suspendContract(Contract $contract, User $suspendedBy, ?string $comment = null): Contract
    {
        return DB::transaction(function () use ($contract, $suspendedBy, $comment) {
            if ($contract->statut !== ContractStatus::ACTIF->value) {
                throw new \Exception("Seul un contrat actif peut être suspendu.");
            }

            $contract->suspendre();
            ContractHistory::logStatusChange($contract, 'suspended', $suspendedBy, $comment);

            return $contract->fresh();
        });
    }

    /**
     * Réactiver un contrat
     */
    public function reactivateContract(Contract $contract, User $reactivatedBy, ?string $comment = null): Contract
    {
        return DB::transaction(function () use ($contract, $reactivatedBy, $comment) {
            if ($contract->statut !== ContractStatus::SUSPENDU->value) {
                throw new \Exception("Seul un contrat suspendu peut être réactivé.");
            }

            $contract->reactiver();
            ContractHistory::logStatusChange($contract, 'reactivated', $reactivatedBy, $comment);

            return $contract->fresh();
        });
    }

    /**
     * Terminer un contrat
     */
    public function terminateContract(Contract $contract, User $terminatedBy, ?string $comment = null): Contract
    {
        return DB::transaction(function () use ($contract, $terminatedBy, $comment) {
            if ($contract->statut !== ContractStatus::ACTIF->value) {
                throw new \Exception("Seul un contrat actif peut être terminé.");
            }

            $contract->marquerCommeTermine();
            ContractHistory::logStatusChange($contract, 'terminated', $terminatedBy, $comment);

            return $contract->fresh();
        });
    }

    /**
     * Résilier un contrat
     */
    public function terminateContractEarly(Contract $contract, User $terminatedBy, ?string $comment = null): Contract
    {
        return DB::transaction(function () use ($contract, $terminatedBy, $comment) {
            if ($contract->statut !== ContractStatus::ACTIF->value) {
                throw new \Exception("Seul un contrat actif peut être résilié.");
            }

            $contract->marquerCommeResilie();
            ContractHistory::logStatusChange($contract, 'terminated', $terminatedBy, $comment ?? 'Résiliation anticipée');

            return $contract->fresh();
        });
    }

    /**
     * Obtenir les contrats expirant bientôt
     */
    public function getExpiringContracts(string $entrepriseId, int $days = 30)
    {
        return Contract::forEntreprise($entrepriseId)
            ->expiringSoon($days)
            ->with(['user', 'entreprise'])
            ->get()
            ->map(function ($contract) {
                $contract->jours_restants = $contract->jours_restants;
                return $contract;
            });
    }

    /**
     * Obtenir les contrats expirés
     */
    public function getExpiredContracts(string $entrepriseId)
    {
        return Contract::forEntreprise($entrepriseId)
            ->expired()
            ->with(['user', 'entreprise'])
            ->get();
    }

    /**
     * Obtenir l'historique d'un contrat
     */
    public function getContractHistory(Contract $contract)
    {
        return $contract->histories()
            ->with('modifiedBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir tous les contrats d'un utilisateur
     */
    public function getUserContracts(int $userId)
    {
        return Contract::forUser($userId)
            ->with(['entreprise', 'createdBy', 'updatedBy'])
            ->get();
    }

    /**
     * Vérifier et mettre à jour automatiquement les contrats expirés
     * et suspendre les contrats des utilisateurs inactifs
     */
    public function checkAndUpdateExpiredContracts(string $entrepriseId): int
    {
        $count = 0;

        // 1. Terminer les contrats expirés
        $expiredContracts = $this->getExpiredContracts($entrepriseId);

        foreach ($expiredContracts as $contract) {
            $contract->marquerCommeTermine();
            ContractHistory::create([
                'contract_id' => $contract->id,
                'user_id' => $contract->user_id,
                'entreprise_id' => $contract->entreprise_id,
                'action' => 'terminated',
                'changes' => [
                    'statut' => [
                        'before' => ContractStatus::ACTIF->value,
                        'after' => ContractStatus::TERMINE->value,
                    ],
                ],
                'comment' => 'Terminé automatiquement (date de fin dépassée)',
                'modified_by' => $contract->user_id, // Système
            ]);
            $count++;
        }

        // 2. Suspendre les contrats des utilisateurs inactifs (statu_user = 0)
        $inactiveUserContracts = Contract::where('entreprise_id', $entrepriseId)
            ->where('statut', ContractStatus::ACTIF->value)
            ->whereHas('user', function ($query) {
                $query->where('statu_user', 0);
            })
            ->with('user')
            ->get();

        foreach ($inactiveUserContracts as $contract) {
            $previousStatus = $contract->statut;
            $contract->statut = ContractStatus::SUSPENDU->value;
            $contract->save();

            ContractHistory::create([
                'contract_id' => $contract->id,
                'user_id' => $contract->user_id,
                'entreprise_id' => $contract->entreprise_id,
                'action' => 'suspended',
                'changes' => [
                    'statut' => [
                        'before' => $previousStatus,
                        'after' => ContractStatus::SUSPENDU->value,
                    ],
                ],
                'comment' => 'Suspendu automatiquement (utilisateur inactif)',
                'modified_by' => $contract->user_id, // Système
            ]);
            $count++;
        }

        return $count;
    }
}
