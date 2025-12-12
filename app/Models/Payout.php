<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payout extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'payout_account_id',
        'gross_amount_xaf',
        'platform_fee_xaf',
        'net_amount_xaf',
        'amount_xaf',
        'status',
        'reference',
        'paid_at',
        'proof_url',
        'period_start',
        'period_end',
        'meta',
    ];

    protected $casts = [
        'gross_amount_xaf' => 'integer',
        'platform_fee_xaf' => 'integer',
        'net_amount_xaf' => 'integer',
        'amount_xaf' => 'integer',
        'paid_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(PayoutAccount::class, 'payout_account_id');
    }
}
