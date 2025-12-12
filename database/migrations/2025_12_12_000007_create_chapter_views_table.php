<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_views', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('chapter_id');
            $table->uuid('series_id')->nullable();
            $table->uuid('creator_id')->nullable();
            $table->string('year_month', 7); // YYYY-MM
            $table->unsignedInteger('duration_seconds')->default(0); // cumulé
            $table->unsignedTinyInteger('completion_ratio')->default(0); // 0-100
            $table->timestamp('first_viewed_at')->nullable();
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('counted_at')->nullable(); // quand la vue est validée
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'chapter_id', 'year_month']);
            $table->index(['creator_id', 'year_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_views');
    }
};
