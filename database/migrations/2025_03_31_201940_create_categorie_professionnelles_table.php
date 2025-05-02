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
        Schema::create('categorie_professionnelles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom_categorie_professionnelle');
            $table->string('description')->nullable();
            $table->string('statut')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie_professionnelles');
    }
};
