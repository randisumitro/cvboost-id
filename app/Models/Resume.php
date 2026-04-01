<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'template_id',
        'title',
        'personal_data',
        'experiences',
        'educations',
        'skills',
        'primary_color',
        'font_family',
        'is_completed',
        'ats_score',
        'ats_feedback',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'personal_data' => 'array',
        'experiences' => 'array',
        'educations' => 'array',
        'skills' => 'array',
        'ats_feedback' => 'array',
        'is_completed' => 'boolean',
        'ats_score' => 'integer',
        'download_count' => 'integer',
        'last_downloaded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }

    public function scopeByUser($query, $userId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }

        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        }

        return $query->whereNull('user_id');
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function incrementDownload()
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    public function getFullNameAttribute()
    {
        return $this->personal_data['name'] ?? 'Untitled';
    }

    public function getEmailAttribute()
    {
        return $this->personal_data['email'] ?? '';
    }

    public function getPhoneAttribute()
    {
        return $this->personal_data['phone'] ?? '';
    }

    public function getAddressAttribute()
    {
        return $this->personal_data['address'] ?? '';
    }

    public function getLinkedInAttribute()
    {
        return $this->personal_data['linkedin'] ?? '';
    }

    public function getPortfolioAttribute()
    {
        return $this->personal_data['portfolio'] ?? '';
    }

    public function getSummaryAttribute()
    {
        return $this->personal_data['summary'] ?? '';
    }
}
