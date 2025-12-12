<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ChapterView extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'chapter_id',
        'series_id',
        'creator_id',
        'year_month',
        'duration_seconds',
        'completion_ratio',
        'first_viewed_at',
        'last_viewed_at',
        'counted_at',
        'meta',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'completion_ratio' => 'integer',
        'first_viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'counted_at' => 'datetime',
        'meta' => 'array',
    ];
}
