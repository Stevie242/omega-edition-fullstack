<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('payout_account_id')->nullable();
            $table->bigInteger('gross_amount_xaf')->default(0);
            $table->bigInteger('platform_fee_xaf')->default(0);
            $table->bigInteger('net_amount_xaf')->default(0);
            $table->bigInteger('amount_xaf')->default(0); // paid or payable amount
            $table->string('status', 30)->default('pending'); // pending, processing, paid, failed
            $table->string('reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('proof_url')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('payout_account_id')->references('id')->on('payout_accounts')->nullOnDelete();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
