<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youtube_channels', function (Blueprint $table) {
            $table->id();
            $table->string('channel_id')->unique();
            $table->string('channel_name');
            $table->string('channel_url');
            $table->string('channel_thumbnail')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youtube_channels');
    }
};
