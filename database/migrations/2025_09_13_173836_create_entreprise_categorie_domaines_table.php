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
        Schema::create('entreprise_categorie_domaines', function (Blueprint $table) {
            $table->uuid('entreprise_id');
            $table->uuid('categorie_domaine_id');
            $table->string('statut')->default('1');
            $table->timestamps();
        
            $table->primary(['entreprise_id','categorie_domaine_id']);
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->foreign('categorie_domaine_id')->references('id')->on('categorie_domaines')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprise_categorie_domaines');
    }
};
