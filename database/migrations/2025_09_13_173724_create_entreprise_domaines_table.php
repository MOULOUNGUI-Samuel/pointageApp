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
        Schema::create('entreprise_domaines', function (Blueprint $table) {
            $table->uuid('entreprise_id');
            $table->uuid('domaine_id');
            $table->string('statut')->default('1'); // 1=actif, 0=inactif
            $table->timestamps();
        
            $table->primary(['entreprise_id','domaine_id']);
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->foreign('domaine_id')->references('id')->on('domaines')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprise_domaines');
    }
};
