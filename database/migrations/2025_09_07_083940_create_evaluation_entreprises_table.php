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
        Schema::create('evaluation_entreprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entreprise_id');
            $table->uuid('item_id');
            $table->string('url_document')->nullable();
            $table->text('commentaire')->nullable();
            $table->uuid('created_by');
            $table->uuid('revised_by')->nullable();
            $table->text('cause_rejet')->nullable();
            $table->string('statut')->default('en_attente');
            $table->timestamps();

            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('revised_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_entreprises');
    }
};
