<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('youtube_channels', function (Blueprint $table) {
            $table->dropUnique(['channel_id']);
            $table->unique(['user_id', 'channel_id']);
        });
    }

    public function down(): void
    {
        Schema::table('youtube_channels', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'channel_id']);
            $table->unique(['channel_id']);
        });
    }
};
