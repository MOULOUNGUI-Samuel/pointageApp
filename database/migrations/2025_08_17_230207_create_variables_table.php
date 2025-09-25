<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('variables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('categorie_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();
            $table->string('nom_variable');
            $table->decimal('tauxVariable', 6, 2)->nullable()->default(null)->change();
            $table->decimal('tauxVariableEntreprise', 6, 2)->nullable()->default(null)->change();
            // libre, à typer plus tard (ex: enum) si besoin
            $table->string('type', 50)->nullable();
            $table->boolean('statutVariable')->default(false);
            $table->boolean('variableImposable')->default(false);
            $table->boolean('statut')->default(true)->index();
            $table->timestamps();

            $table->index(['categorie_id', 'statut']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('variables');
    }
};

