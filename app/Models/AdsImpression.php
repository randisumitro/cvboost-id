<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsImpression extends Model
{
    protected $fillable = [
        'user_id',
        'ad_type',
        'page_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, $adType)
    {
        return $query->where('ad_type', $adType);
    }

    public function scopeBanner($query)
    {
        return $query->where('ad_type', 'banner');
    }

    public function scopeInterstitial($query)
    {
        return $query->where('ad_type', 'interstitial');
    }

    public function scopeVideo($query)
    {
        return $query->where('ad_type', 'video');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public static function trackImpression($adType, $pageUrl, $userId = null)
    {
        return self::create([
            'user_id' => $userId,
            'ad_type' => $adType,
            'page_url' => $pageUrl,
        ]);
    }
}
