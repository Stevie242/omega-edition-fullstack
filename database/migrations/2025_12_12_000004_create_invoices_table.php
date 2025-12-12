<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('number')->unique();
            $table->string('period_label')->nullable();
            $table->bigInteger('amount_xaf')->default(0);
            $table->string('status', 30)->default('paid'); // paid, unpaid, cancelled
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('pdf_url')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
