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
        Schema::create('demande_intervention_recipients', function (Blueprint $table) {
            $table->uuid('demande_intervention_id');
            $table->uuid('user_id');
            $table->string('type')->default('to'); 
            $table->timestamp('selected_at')->nullable();
        
            $table->primary(['demande_intervention_id', 'user_id']);
            $table->foreign('demande_intervention_id')
                  ->references('id')->on('demande_interventions')
                  ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
            $table->index(['user_id','type']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_intervention_recipients');
    }
};
