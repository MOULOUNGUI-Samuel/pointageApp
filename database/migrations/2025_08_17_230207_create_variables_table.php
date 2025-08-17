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
            // libre, à typer plus tard (ex: enum) si besoin
            $table->string('type', 50)->nullable();
            $table->boolean('statut')->default(true)->index();
            $table->timestamps();

            $table->index(['categorie_id', 'statut']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('variables');
    }
};

