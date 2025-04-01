<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('entreprise_id')->nullable();
        $table->string('nom');
        $table->string('prenom');
        $table->string('matricule')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->date('date_naissance')->nullable();
        $table->string('fonction')->nullable();
        $table->string('role_user');
        $table->boolean('statut')->default(1);
        $table->timestamps();

        $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
