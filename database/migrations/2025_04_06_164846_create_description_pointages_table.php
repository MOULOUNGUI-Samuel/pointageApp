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
        Schema::create('description_pointages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pointage_intermediaire_id')->nullable();
            $table->text('description');
            $table->boolean('statut')->default(0);
            $table->timestamps();

            $table->foreign('pointage_intermediaire_id')
                ->references('id')
                ->on('pointages_intermediaires')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('description_pointages');
    }
};
