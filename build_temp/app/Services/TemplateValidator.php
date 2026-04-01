<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Facades\Cache;

/**
 * Template Validator Service
 * Ensures only whitelisted templates can be rendered
 */
class TemplateValidator
{
    private const CACHE_KEY = 'allowed_template_slugs';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Get list of allowed template slugs from database
     */
    public function getAllowedSlugs(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Template::active()
                ->pluck('slug')
                ->map(fn ($slug) => strtolower($slug))
                ->toArray();
        });
    }

    /**
     * Check if template slug is allowed
     */
    public function isAllowed(string $slug): bool
    {
        $allowedSlugs = $this->getAllowedSlugs();
        return in_array(strtolower($slug), $allowedSlugs, true);
    }

    /**
     * Validate template and return safe view name
     */
    public function getSafeViewName(string $slug): string
    {
        if (!$this->isAllowed($slug)) {
            // Log attempted slug injection
            \Log::warning('Template slug injection attempt', [
                'slug' => $slug,
                'ip' => request()->ip(),
                'user' => auth()->id(),
            ]);

            // Return default template
            return 'resume.templates.modern-professional';
        }

        $viewName = 'resume.templates.' . $slug;

        // Double-check view exists
        if (!view()->exists($viewName)) {
            return 'resume.templates.modern-professional';
        }

        return $viewName;
    }

    /**
     * Clear cache (call when templates change)
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
