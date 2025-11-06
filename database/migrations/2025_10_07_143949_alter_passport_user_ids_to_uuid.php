<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // oauth_auth_codes.user_id
        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            // char(36) / string(36) = UUID
            $table->string('user_id', 36)->nullable()->change();
        });

        // oauth_access_tokens.user_id
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->string('user_id', 36)->nullable()->change();
        });

        // (facultatif mais conseillé si tu l'utilises)
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->string('user_id', 36)->nullable()->change();
        });

        // (idem)
        Schema::table('oauth_personal_access_clients', function (Blueprint $table) {
            $table->string('user_id', 36)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('oauth_auth_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
        Schema::table('oauth_personal_access_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }
};
