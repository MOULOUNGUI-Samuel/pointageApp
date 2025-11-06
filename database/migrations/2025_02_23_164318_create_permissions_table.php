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
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreignUuid('entreprise_id')->constrained('entreprises')->onDelete('cascade');

            $table->foreignUuid('groupe_id')->references('id')->on('groupe_permissions')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
