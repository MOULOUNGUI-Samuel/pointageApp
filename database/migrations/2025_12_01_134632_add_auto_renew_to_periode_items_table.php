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
        Schema::table('periode_items', function (Blueprint $table) {
            $table->boolean('auto_renew')->default(false)->after('statut')
                ->comment('Relancer automatiquement la période après échéance');
            $table->integer('renew_duration_value')->nullable()->after('auto_renew')
                ->comment('Durée de renouvellement (valeur)');
            $table->enum('renew_duration_unit', ['days', 'months', 'years'])->default('months')->after('renew_duration_value')
                ->comment('Unité de durée (jours, mois, années)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_items', function (Blueprint $table) {
            $table->dropColumn(['auto_renew', 'renew_duration_value', 'renew_duration_unit']);
        });
    }
};
