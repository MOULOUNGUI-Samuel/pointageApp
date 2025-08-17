<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('periodes_paie', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('statut')->default(true)->index();
            $table->foreignUuid('entreprise_id')
                  ->constrained('entreprises')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->index(['entreprise_id', 'date_debut', 'date_fin']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('periodes_paie');
    }
};

