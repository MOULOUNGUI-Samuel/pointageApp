<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            // Clé primaire UUID
            $table->uuid('id')->primary();

            // UUID foreign keys pour users et entreprises
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('entreprise_id');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');

            // Informations du contrat
            $table->string('type_contrat'); // CDI, CDD, Stage, Apprentissage, etc.
            $table->date('date_debut');
            $table->date('date_fin')->nullable();

            // Informations financières
            $table->decimal('salaire_base', 15, 2)->nullable();
            $table->string('mode_paiement')->nullable();
            $table->text('avantages')->nullable(); // JSON ou texte libre pour les avantages

            // Statut et versioning
            $table->string('statut')->default('actif'); // brouillon, actif, suspendu, termine, resilie
            $table->integer('version')->default(1);

            // Parent contract - UUID au lieu de foreignId
            $table->uuid('parent_contract_id')->nullable();
            $table->foreign('parent_contract_id')->references('id')->on('contracts')->onDelete('set null');

            // Métadonnées
            $table->text('notes')->nullable();
            $table->string('document_path')->nullable(); // Chemin vers le PDF du contrat

            // Audit - UUID foreign keys
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->uuid('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Index pour les requêtes fréquentes
            $table->index(['user_id', 'statut']);
            $table->index(['entreprise_id', 'statut']);
            $table->index('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
