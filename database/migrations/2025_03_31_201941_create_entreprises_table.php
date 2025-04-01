<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom_entreprise');
            $table->time('heure_ouverture')->nullable();
            $table->time('heure_fin')->nullable();
            $table->string('logo')->nullable();
            $table->time('heure_debut_pose')->nullable();
            $table->time('heure_fin_pose')->nullable();
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->integer('rayon_autorise')->default(100); // en mètres
            $table->boolean('statut')->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
