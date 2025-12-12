<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creator_revenue_monthlies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('creator_id');
            $table->string('year_month', 7); // YYYY-MM
            $table->unsignedBigInteger('validated_views')->default(0);
            $table->unsignedBigInteger('gross_amount_xaf')->default(0);
            $table->unsignedBigInteger('platform_fee_xaf')->default(0);
            $table->unsignedBigInteger('tax_withheld_xaf')->default(0);
            $table->unsignedBigInteger('net_amount_xaf')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['creator_id', 'year_month']);
            $table->index(['year_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_revenue_monthlies');
    }
};
