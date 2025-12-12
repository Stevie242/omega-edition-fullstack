<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payout_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('type', 50); // bank, card, mobile_money
            $table->string('label')->nullable();
            $table->string('holder_first_name')->nullable();
            $table->string('holder_last_name')->nullable();
            $table->string('details')->nullable(); // IBAN, phone, masked card
            $table->boolean('is_default')->default(false);
            $table->string('status', 30)->default('pending'); // pending, verified, rejected
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_accounts');
    }
};
