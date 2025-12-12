<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Invoice extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'number',
        'period_label',
        'amount_xaf',
        'status',
        'paid_at',
        'payment_method',
        'pdf_url',
        'meta',
    ];

    protected $casts = [
        'amount_xaf' => 'integer',
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
