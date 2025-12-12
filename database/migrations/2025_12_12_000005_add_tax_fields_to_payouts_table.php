<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->bigInteger('tax_withheld_xaf')->default(0)->after('platform_fee_xaf');
            $table->unsignedInteger('tax_rate_bps')->nullable()->after('tax_withheld_xaf'); // basis points (1% = 100)
        });
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn(['tax_withheld_xaf', 'tax_rate_bps']);
        });
    }
};
