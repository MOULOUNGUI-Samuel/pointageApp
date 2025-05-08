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
    Schema::create('users', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('entreprise_id')->nullable();
        $table->uuid('module_id')->nullable();
        $table->uuid('role_id')->nullable();
        $table->uuid('service_id')->nullable();
        $table->uuid('ville_id')->nullable();
        $table->uuid('pays_id')->nullable();
        $table->uuid('categorie_professionel_id')->nullable();
        $table->string('photo')->nullable();
        $table->string('niveau_etude')->nullable();
        $table->string('telephone')->nullable();
        $table->string('telephone_professionnel')->nullable();
        $table->string('adresse')->nullable();
        $table->string('adresse_complementaire')->nullable();
        $table->string('code_postal')->nullable();
        $table->string('nom');
        $table->string('prenom');
        $table->string('matricule')->unique();
        $table->string('email')->unique();
        $table->string('email_professionnel')->nullable();
        $table->string('password');
        $table->date('date_naissance')->nullable();
        $table->date('date_embauche')->nullable();
        $table->string('lieu_naissance')->nullable();
        $table->string('nationalite')->nullable();
        $table->string('numero_securite_sociale')->nullable();
        $table->string('etat_civil')->nullable();
        $table->integer('nombre_enfant')->nullable();
        $table->string('fonction')->nullable();
        $table->string('cv')->nullable();
        $table->string('permis_conduire')->nullable();
        $table->string('piece_identite')->nullable();
        $table->string('diplome')->nullable();
        $table->string('certificat_travail')->nullable();
        $table->string('type_contrat')->nullable();
        $table->integer('salaire')->nullable();
        $table->string('mode_paiement')->nullable();
        $table->string('iban')->nullable();
        $table->string('superieur_hierarchique')->nullable();
        $table->string('bic')->nullable();
        $table->string('titulaire_compte')->nullable();
        $table->string('nom_banque')->nullable();
        $table->string('nom_agence')->nullable();
        $table->boolean('statu_user')->default(1);
        $table->boolean('statut')->default(1);
        $table->string('nom_completaire')->nullable();
        $table->string('lien_completaire')->nullable();
        $table->string('contact_completaire')->nullable();
        $table->string('formation_completaire')->nullable();
        $table->text('commmentaire_completaire')->nullable();
        $table->text('competence')->nullable();
        $table->timestamps();
        $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
        $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        $table->foreign('ville_id')->references('id')->on('villes')->onDelete('set null');
        $table->foreign('pays_id')->references('id')->on('pays')->onDelete('set null');
        $table->foreign('categorie_professionel_id')->references('id')->on('categorie_professionnelles')->onDelete('set null');
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
