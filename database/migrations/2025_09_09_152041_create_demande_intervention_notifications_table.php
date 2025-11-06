<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('demande_intervention_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('demande_intervention_id');
            $table->uuid('user_id');
            $table->string('channel', 20)->default('mail');
            $table->string('mailable')->nullable();
            $table->string('status', 20)->default('queued');  // queued|sent|failed
            $table->string('transport_id')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // NOMS COURTS POUR LES CONTRAINTES
            $table->foreign('demande_intervention_id', 'di_notif_demande_fk')
                  ->references('id')->on('demande_interventions')
                  ->onDelete('cascade');

            $table->foreign('user_id', 'di_notif_user_fk')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            // NOMS COURTS POUR LES INDEX
            $table->index(['demande_intervention_id', 'user_id', 'status'], 'di_notif_dus_idx');
            $table->index('created_at', 'di_notif_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_intervention_notifications');
    }
};
