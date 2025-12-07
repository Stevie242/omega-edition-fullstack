<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('commentable'); // commentable_id + commentable_type
            $table->foreignUuid('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->text('body');
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('dislikes_count')->default(0);
            $table->timestamps();

            $table->index(['commentable_id', 'commentable_type']);
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('likeable'); // likeable_id + likeable_type
            $table->boolean('is_like')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('comments');
    }
};
