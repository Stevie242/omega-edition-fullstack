<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TaxProfile extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'mode', // self, withheld
        'country',
        'tax_id',
        'tax_rate_bps',
        'metadata',
    ];

    protected $casts = [
        'tax_rate_bps' => 'integer',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
