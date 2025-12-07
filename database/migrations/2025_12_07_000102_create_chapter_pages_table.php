<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('size_kb')->nullable();
            $table->timestamps();

            $table->index(['chapter_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_pages');
    }
};
