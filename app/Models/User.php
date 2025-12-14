<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\ProfileCreator;
use App\Models\UserPreference;
use App\Models\PayoutAccount;
use App\Models\Payout;
use App\Models\Invoice;
use App\Models\TaxProfile;
use App\Models\ReaderProfile;
use App\Models\ReaderSubscription;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_locked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_locked' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }

    public function isReader(): bool
    {
        return $this->role === 'reader';
    }

    public function creatorProfile()
    {
        return $this->hasOne(ProfileCreator::class);
    }

    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function readerProfile()
    {
        return $this->hasOne(ReaderProfile::class);
    }

    public function payoutAccounts()
    {
        return $this->hasMany(PayoutAccount::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function readerSubscription()
    {
        return $this->hasOne(ReaderSubscription::class);
    }

    public function taxProfile()
    {
        return $this->hasOne(TaxProfile::class);
    }
}
