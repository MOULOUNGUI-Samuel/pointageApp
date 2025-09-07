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
        Schema::create('domaines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_add_id')->nullable();;
            $table->uuid('user_update_id')->nullable();
            $table->string('nom_domaine');
            $table->string('description')->nullable();
            $table->string('statut')->default(1);
            $table->foreign('user_add_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_update_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domaines');
    }
};
