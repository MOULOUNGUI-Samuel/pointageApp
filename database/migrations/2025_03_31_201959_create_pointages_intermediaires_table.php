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
        Schema::create('pointages_intermediaire', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pointage_id')->nullable();
            $table->time('heure_sortie')->nullable();
            $table->time('heure_entrer')->nullable();
            $table->boolean('statut')->default(1);
            $table->timestamps();

            $table->foreign('pointage_id')->references('id')->on('pointages')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages_intermediaires');
    }
};
