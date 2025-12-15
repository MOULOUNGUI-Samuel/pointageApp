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
        Schema::create('contract_histories', function (Blueprint $table) {
            // Clé primaire UUID
            $table->uuid('id')->primary();

            // Foreign key vers contracts (UUID)
            $table->uuid('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');

            // UUID foreign keys pour users et entreprises
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('entreprise_id');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');

            // Type d'action
            $table->string('action'); // created, updated, renewed, suspended, terminated, reactivated

            // Données avant/après modification (JSON)
            $table->json('changes')->nullable(); // Stocke les changements (before/after)

            // Métadonnées
            $table->text('comment')->nullable(); // Commentaire sur la modification

            // UUID foreign key pour modified_by
            $table->uuid('modified_by');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();

            // Index pour les requêtes fréquentes
            $table->index(['contract_id', 'created_at']);
            $table->index('modified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_histories');
    }
};
