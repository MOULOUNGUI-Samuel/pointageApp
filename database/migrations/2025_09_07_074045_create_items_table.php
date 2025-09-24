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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('categorie_domaine_id');
            // type statique
            $table->string('type', 20)->default('texte');
            $table->uuid('user_add_id')->nullable();
            $table->uuid('user_update_id')->nullable();
            $table->string('nom_item');
            $table->string('description')->nullable();
            $table->string('statut')->default(1);
            $table->timestamps();

            $table->foreign('categorie_domaine_id')->references('id')->on('categorie_domaines')->onDelete('cascade');
            $table->foreign('user_add_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_update_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
