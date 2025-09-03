<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // L'utilisateur concerné (suppression en cascade)
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('type');
            $table->string('status')->default('brouillon');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->text('reason')->nullable();

            // Pièce jointe de la demande
            $table->string('attachment_path')->nullable();

            // Validation / décision
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('justification')->nullable();

            // Retour d'absence
            $table->timestamp('return_confirmed_at')->nullable();
            $table->boolean('returned_on_time')->default(true);
            $table->text('return_notes')->nullable();
            $table->string('return_attachment_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
