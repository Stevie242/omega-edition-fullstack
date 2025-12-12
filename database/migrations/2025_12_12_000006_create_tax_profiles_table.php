<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('mode', 20)->default('self'); // self, withheld
            $table->string('country', 2)->nullable();
            $table->string('tax_id')->nullable();
            $table->unsignedInteger('tax_rate_bps')->nullable(); // 1% = 100 bps
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_profiles');
    }
};
