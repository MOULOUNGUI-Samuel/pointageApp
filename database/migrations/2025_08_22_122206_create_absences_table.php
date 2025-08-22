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
        Schema::create('absences', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // ADAPTATION : Utilisation de 'user_id' pour être cohérent avec votre table 'pointages'.
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');

            $table->string('type');
            $table->enum('status', ['demandé', 'approuvé', 'rejeté'])->default('demandé');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->text('reason')->nullable();

            // ADAPTATION : La personne qui approuve est aussi un 'user'.
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};