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
            $table->uuid('type_item_id');
            $table->string('nom_item');
            $table->string('description')->nullable();
            $table->string('statut')->default(1);
            $table->timestamps();

            $table->foreign('categorie_domaine_id')->references('id')->on('categorie_domaines')->onDelete('cascade');
            $table->foreign('type_item_id')->references('id')->on('type_items')->onDelete('cascade');
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
