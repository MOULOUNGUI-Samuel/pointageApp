<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('variable_periode_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignUuid('periode_paie_id')
                  ->constrained('periodes_paie')
                  ->cascadeOnDelete();

            $table->foreignUuid('variable_id')
                  ->constrained('variables')
                  ->cascadeOnDelete();

            $table->decimal('montant', 15, 2)->default(0);
            $table->boolean('statut')->default(true)->index();
            $table->timestamps();

            // Empêche les doublons pour un même user/période/variable
            $table->unique(['user_id', 'periode_paie_id', 'variable_id'], 'uq_user_periode_variable');
        });
    }

    public function down(): void {
        Schema::dropIfExists('variable_periode_users');
    }
};

