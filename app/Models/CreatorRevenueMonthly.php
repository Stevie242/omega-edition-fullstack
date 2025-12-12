<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CreatorRevenueMonthly extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'creator_id',
        'year_month',
        'validated_views',
        'gross_amount_xaf',
        'platform_fee_xaf',
        'tax_withheld_xaf',
        'net_amount_xaf',
        'meta',
    ];

    protected $casts = [
        'validated_views' => 'integer',
        'gross_amount_xaf' => 'integer',
        'platform_fee_xaf' => 'integer',
        'tax_withheld_xaf' => 'integer',
        'net_amount_xaf' => 'integer',
        'meta' => 'array',
    ];
}
