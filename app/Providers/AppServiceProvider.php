<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limit resume creation: 5 per minute per IP, 10 per minute per user
        RateLimiter::for('resume-creation', function ($request) {
            if ($request->user()) {
                return Limit::perMinute(10)->by($request->user()->id);
            }
            return Limit::perMinute(5)->by($request->ip());
        });

        // Rate limit PDF downloads: 3 per minute per IP, 10 per minute per user
        RateLimiter::for('pdf-download', function ($request) {
            if ($request->user()) {
                return Limit::perMinute(10)->by($request->user()->id);
            }
            return Limit::perMinute(3)->by($request->ip());
        });

        // Rate limit ATS scoring: 5 per minute per user
        RateLimiter::for('ats-score', function ($request) {
            if ($request->user()) {
                return Limit::perMinute(5)->by($request->user()->id);
            }
            return Limit::perMinute(2)->by($request->ip());
        });

        // Rate limit API endpoints
        RateLimiter::for('api', function ($request) {
            if ($request->user()) {
                return Limit::perMinute(30)->by($request->user()->id);
            }
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
