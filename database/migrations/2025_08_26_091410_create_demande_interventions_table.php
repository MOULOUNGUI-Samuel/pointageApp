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
        Schema::create('demande_interventions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Entreprise assignée (assure-toi que la table s'appelle bien "entreprises")
            $table->foreignUuid('entreprise_id')
                  ->constrained('entreprises')
                  ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('titre');
            $table->text('description')->nullable();

            // Dans ton formulaire c'est "required" — on le laisse NOT NULL
            $table->date('date_souhaite');

            // Chemin du fichier téléversé (storage/app/public/...)
            $table->string('piece_jointe_path')->nullable();

            // Optionnel mais pratique pour le workflow
            $table->string('statut')->default('en_attente');

            $table->timestamps();
            $table->softDeletes();

            // Index utiles
            $table->index(['entreprise_id', 'date_souhaite']);
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_interventions');
    }
};
