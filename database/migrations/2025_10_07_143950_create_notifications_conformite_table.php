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
        Schema::create('notifications_conformite', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entreprise_id');
            $table->uuid('user_id')->nullable(); // utilisateur spécifique ou null = tous les users de l'entreprise
            
            // Type de notification
            $table->enum('type', [
                'nouvelle_periode',
                'validation',
                'refus',
                'rappel_echeance',
                'periode_expiree',
                'nouvelle_soumission',
                'resoumission',
                'rapport_quotidien'
            ]);
            
            $table->string('titre');
            $table->text('message');
            
            // Références contextuelles (optionnelles)
            $table->uuid('item_id')->nullable();
            $table->uuid('periode_item_id')->nullable();
            $table->uuid('soumission_id')->nullable();
            $table->uuid('domaine_id')->nullable();
            $table->uuid('categorie_domaine_id')->nullable();
            
            // Métadonnées additionnelles (JSON pour flexibilité)
            $table->json('metadata')->nullable(); // ex: jours restants, url d'action, etc.
            
            // Statut de lecture
            $table->boolean('lue')->default(0);
            $table->timestamp('lue_le')->nullable();
            
            // Statut d'envoi email
            $table->boolean('email_envoye')->default(0);
            $table->timestamp('email_envoye_le')->nullable();
            
            // Priorité (pour trier les notifications)
            $table->enum('priorite', ['basse', 'normale', 'haute', 'urgente'])->default('normale');
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('periode_item_id')->references('id')->on('periode_items')->onDelete('set null');
            $table->foreign('soumission_id')->references('id')->on('conformity_submissions')->onDelete('set null');
            $table->foreign('domaine_id')->references('id')->on('domaines')->onDelete('set null');
            $table->foreign('categorie_domaine_id')->references('id')->on('categorie_domaines')->onDelete('set null');
            
            // Index pour performance
            $table->index(['entreprise_id', 'lue']);
            $table->index(['user_id', 'lue']);
            $table->index(['type', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications_conformite');
    }
};