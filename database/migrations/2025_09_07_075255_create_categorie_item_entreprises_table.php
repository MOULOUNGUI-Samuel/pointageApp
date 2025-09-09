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
        Schema::create('categorie_item_entreprises', function (Blueprint $table) {
            $table->uuid('item_id');
            $table->uuid('entreprise_id');
            $table->uuid('categorie_id');
            $table->string('statut')->default(1);
            $table->timestamps();

            // 🔹 Définition des clés étrangères
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');

            $table->foreign('entreprise_id')
                ->references('id')
                ->on('entreprises')
                ->onDelete('cascade');
            $table->foreign('categorie_id')
                ->references('id')
                ->on('categorie_domaines')
                ->onDelete('cascade');
            // 🔹 Clé primaire composite (évite les doublons)
            $table->primary(['item_id', 'entreprise_id', 'categorie_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie_item_entreprises');
    }
};
