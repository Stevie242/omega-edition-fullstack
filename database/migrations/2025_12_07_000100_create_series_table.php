<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['manga', 'webtoon'])->default('manga');
            $table->enum('status', ['ongoing', 'hiatus', 'completed'])->default('ongoing');
            $table->enum('format', ['oneshot', 'miniseries', 'series'])->default('series');
            $table->enum('frequency', ['weekly', 'biweekly', 'monthly', 'irregular'])->default('weekly');
            $table->string('language', 8)->default('fr');
            $table->text('synopsis')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('hero_path')->nullable();
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('dislikes_count')->default(0);
            $table->decimal('rating', 3, 1)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
