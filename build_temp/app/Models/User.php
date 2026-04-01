<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'subscription_status',
        'subscription_expires_at',
        'free_cv_limit',
        'free_ats_limit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'subscription_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function adsImpressions()
    {
        return $this->hasMany(AdsImpression::class);
    }

    public function isPremium()
    {
        return $this->subscription_status === 'premium' &&
               $this->subscription_expires_at &&
               $this->subscription_expires_at > now();
    }

    public function canCreateMoreCVs()
    {
        if ($this->isPremium()) {
            return true;
        }

        $limit = $this->free_cv_limit ?? 2;
        return $this->resumes()->count() < $limit;
    }

    public function canUseATS()
    {
        if ($this->isPremium()) {
            return true;
        }

        return $this->free_ats_limit > 0;
    }
}
