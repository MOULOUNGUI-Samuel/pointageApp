<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void {
            Schema::create('conformity_submissions', function (Blueprint $t) {
                $t->uuid('id')->primary();
                $t->uuid('entreprise_id');
                $t->uuid('item_id');
                $t->uuid('periode_item_id')->nullable(); // période ciblée (si active au moment de la déclaration)
                $t->uuid('submitted_by');                // utilisateur déclarant
                $t->uuid('reviewed_by')->nullable();     // utilisateur validateur
                $t->enum('status', ['brouillon','soumis','approuvé','rejeté'])->default('soumis');
                $t->timestamp('submitted_at')->nullable();
                $t->timestamp('reviewed_at')->nullable();
                $t->text('reviewer_notes')->nullable();
                $t->timestamps();
    
                $t->foreign('entreprise_id')->references('id')->on('entreprises')->cascadeOnDelete();
                $t->foreign('item_id')->references('id')->on('items')->cascadeOnDelete();
                $t->foreign('periode_item_id')->references('id')->on('periode_items')->nullOnDelete();
                $t->foreign('submitted_by')->references('id')->on('users')->cascadeOnDelete();
                $t->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            });
    
            Schema::create('conformity_answers', function (Blueprint $t) {
                $t->uuid('id')->primary();
                $t->uuid('submission_id');
                $t->enum('kind', ['texte','documents','liste','checkbox']);
                // Valeurs possibles selon le type
                $t->longText('value_text')->nullable();    // pour "texte"
                $t->json('value_json')->nullable();        // pour liste/checkbox (ex: {"selected":["opt1","opt2"]})
                $t->string('file_path')->nullable();       // pour documents (1 fichier par ligne)
                // lien éventuel vers l’option déclarée (si tu utilises une table item_options)
                $t->uuid('item_option_id')->nullable();
                $t->unsignedInteger('position')->default(1);
                $t->timestamps();
    
                $t->foreign('submission_id')->references('id')->on('conformity_submissions')->cascadeOnDelete();
                $t->foreign('item_option_id')->references('id')->on('item_options')->nullOnDelete();
            });
        }
    
        public function down(): void {
            Schema::dropIfExists('conformity_answers');
            Schema::dropIfExists('conformity_submissions');
        }
};
