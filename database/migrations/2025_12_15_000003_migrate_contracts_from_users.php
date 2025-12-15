<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Contract;
use App\Models\ContractHistory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migration des données existantes de la table users vers contracts
        $users = DB::table('users')
            ->whereNotNull('type_contrat')
            ->orWhereNotNull('date_embauche')
            ->orWhereNotNull('date_fin_contrat')
            ->get();

        foreach ($users as $user) {
            // Créer un contrat seulement si des informations de contrat existent
            if ($user->type_contrat || $user->date_embauche || $user->date_fin_contrat) {
                // Générer un UUID pour le contrat
                $contractId = (string) Str::uuid();

                DB::table('contracts')->insert([
                    'id' => $contractId, // UUID généré manuellement
                    'user_id' => $user->id,
                    'entreprise_id' => $user->entreprise_id,
                    'type_contrat' => $user->type_contrat ?? 'CDI',
                    'date_debut' => $user->date_embauche ?? now(),
                    'date_fin' => $user->date_fin_contrat,
                    'salaire_base' => $user->salaire ?? $user->salairebase,
                    'mode_paiement' => $user->mode_paiement,
                    'statut' => 'actif',
                    'version' => 1,
                    'notes' => 'Contrat migré depuis l\'ancien système',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Créer un historique pour la migration avec UUID
                $historyId = (string) Str::uuid();

                DB::table('contract_histories')->insert([
                    'id' => $historyId, // UUID généré manuellement
                    'contract_id' => $contractId,
                    'user_id' => $user->id,
                    'entreprise_id' => $user->entreprise_id,
                    'action' => 'created',
                    'changes' => json_encode([
                        'note' => 'Contrat migré automatiquement depuis la table users',
                        'after' => [
                            'type_contrat' => $user->type_contrat ?? 'CDI',
                            'date_debut' => $user->date_embauche ?? now(),
                            'date_fin' => $user->date_fin_contrat,
                            'salaire_base' => $user->salaire ?? $user->salairebase,
                        ]
                    ]),
                    'comment' => 'Migration automatique des données depuis la table users',
                    'modified_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Note: On ne supprime PAS les colonnes de la table users pour garder la compatibilité
        // et avoir une période de transition. Vous pourrez les supprimer plus tard avec une autre migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer tous les contrats migrés
        DB::table('contract_histories')->where('action', 'created')
            ->where('comment', 'Migration automatique des données depuis la table users')
            ->delete();

        DB::table('contracts')->where('notes', 'Contrat migré depuis l\'ancien système')->delete();
    }
};
