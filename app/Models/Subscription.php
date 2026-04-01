<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'duration',
        'status',
        'payment_method',
        'payment_details',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'paid')
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function ($q) {
                        $q->where('status', 'paid')
                          ->where('expires_at', '<=', now());
                    });
    }

    public function markAsPaid($paymentMethod = null, $paymentDetails = null)
    {
        $duration = $this->duration;
        $expiresAt = now()->addMonths($duration === 'yearly' ? 12 : 1);

        $this->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'payment_details' => $paymentDetails,
            'paid_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        $this->user->update([
            'subscription_status' => 'premium',
            'subscription_expires_at' => $expiresAt,
        ]);
    }

    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);

        $this->user->update([
            'subscription_status' => 'free',
            'subscription_expires_at' => null,
        ]);
    }

    public function isActive()
    {
        return $this->status === 'paid' &&
               $this->expires_at &&
               $this->expires_at > now();
    }
}
