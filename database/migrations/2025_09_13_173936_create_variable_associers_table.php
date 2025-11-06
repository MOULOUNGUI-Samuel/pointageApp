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
        Schema::create('variable_associers', function (Blueprint $table) {
            $table->uuid('variableBase_id');
            $table->uuid('variableAssocier_id');
            $table->string('statut')->default('1');
            $table->timestamps();
        
            $table->primary(['variableBase_id','variableAssocier_id']);
            $table->foreign('variableBase_id')->references('id')->on('variables')->onDelete('cascade');
            $table->foreign('variableAssocier_id')->references('id')->on('variables')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variable_associers');
    }
};
