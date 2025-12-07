<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProfileCreator extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'display_name',
        'age',
        'gender',
        'nationality',
        'location',
        'languages',
        'headline',
        'bio',
        'signature_style',
        'favorite_formats',
        'portfolio_links',
        'moodboard',
        'website',
        'phone',
        'availability',
        'avatar_url',
        'cover_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
