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
        Schema::create('item_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id');
            $table->enum('kind', ['liste','checkbox']);  // type de l’option
            $table->string('label');                     // libellé visible
            $table->string('value')->nullable();         // valeur technique (facultatif)
            $table->unsignedInteger('position')->default(1);
            $table->string('statut')->default('1');      // 1 actif / 0 inactif
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_options');
    }
};
