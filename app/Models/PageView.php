<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'resume_id',
        'page_url',
        'ip_address',
        'user_agent',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function scopeForResume($query, $resumeId)
    {
        return $query->where('resume_id', $resumeId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }
}
