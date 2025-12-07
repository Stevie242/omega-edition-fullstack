<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_creators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('nationality')->nullable();
            $table->string('location')->nullable();
            $table->string('languages')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('signature_style')->nullable();
            $table->string('favorite_formats')->nullable();
            $table->text('portfolio_links')->nullable();
            $table->text('moodboard')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('availability')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_creators');
    }
};
