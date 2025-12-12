<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReaderProfile extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'avatar_url',
        'birthdate',
        'age',
        'preferred_genres',
        'preferred_formats',
        'preferred_themes',
        'language_preferences',
        'is_completed',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'preferred_genres' => 'array',
        'preferred_formats' => 'array',
        'preferred_themes' => 'array',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
