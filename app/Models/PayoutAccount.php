<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PayoutAccount extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'label',
        'holder_first_name',
        'holder_last_name',
        'details',
        'is_default',
        'status',
        'meta',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
