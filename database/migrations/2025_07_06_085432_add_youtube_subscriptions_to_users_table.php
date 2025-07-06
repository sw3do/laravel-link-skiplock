<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('youtube_subscriptions')->nullable();
            $table->timestamp('subscriptions_updated_at')->nullable();
            $table->string('google_access_token', 1000)->nullable();
            $table->string('google_refresh_token', 1000)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['youtube_subscriptions', 'subscriptions_updated_at', 'google_access_token', 'google_refresh_token']);
        });
    }
};
