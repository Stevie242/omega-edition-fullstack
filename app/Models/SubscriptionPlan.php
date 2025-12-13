<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SubscriptionPlan extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'price_xaf',
        'period_label',
        'description',
        'perks',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'perks' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
}
