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
        Schema::create('categorie_domaines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_add_id');
            $table->uuid('domaine_id');
            $table->uuid('user_update_id')->nullable();
            $table->string('nom_categorie');
            $table->string('code_categorie')->unique();
            $table->string('description')->nullable();
            $table->string('statut')->default(1);
            $table->foreign('user_add_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_update_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('domaine_id')->references('id')->on('domaines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie_domaines');
    }
};
