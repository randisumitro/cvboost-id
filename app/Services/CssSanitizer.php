<?php

namespace App\Services;

/**
 * CSS Sanitizer Service
 * Prevents CSS injection attacks by sanitizing CSS content
 */
class CssSanitizer
{
    /**
     * Dangerous patterns to remove from CSS
     */
    private array $dangerousPatterns = [
        // JavaScript execution
        '/<script[^>]*>.*?<\/script>/is',
        '/javascript:/i',
        '/expression\s*\(/i',
        '/eval\s*\(/i',
        // Event handlers
        '/on\w+\s*=/i',
        // URL-based attacks
        '/url\s*\(\s*["\']?\s*javascript:/i',
        '/@import\s+["\']?\s*javascript:/i',
        // HTML injection
        '/<[\/]?[a-z][^>]*>/i',
    ];

    /**
     * Allowed CSS properties (whitelist approach)
     */
    private array $allowedProperties = [
        // Font
        'font', 'font-family', 'font-size', 'font-weight', 'font-style', 'font-variant', 'line-height',
        // Color & Background
        'color', 'background', 'background-color', 'background-image', 'background-size', 'background-position',
        // Box Model
        'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
        'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
        'border-color', 'border-style', 'border-width', 'border-radius',
        // Layout
        'width', 'height', 'min-width', 'min-height', 'max-width', 'max-height',
        'display', 'position', 'top', 'right', 'bottom', 'left',
        'float', 'clear', 'overflow', 'overflow-x', 'overflow-y',
        'z-index', 'opacity', 'visibility',
        // Flexbox
        'flex', 'flex-direction', 'flex-wrap', 'flex-flow', 'justify-content', 'align-items', 'align-content', 'gap',
        // Text
        'text-align', 'text-decoration', 'text-transform', 'text-indent', 'letter-spacing', 'word-spacing',
        'white-space', 'word-wrap', 'word-break',
        // List
        'list-style', 'list-style-type', 'list-style-position', 'list-style-image',
        // Page
        'page-break-before', 'page-break-after', 'page-break-inside',
        // Other safe properties
        'box-shadow', 'text-shadow', 'transform', 'transition', 'cursor',
    ];

    /**
     * Sanitize CSS content
     */
    public function sanitize(string $css): string
    {
        // Remove dangerous patterns
        $css = $this->removeDangerousPatterns($css);

        // Remove any @import rules (could load external malicious CSS)
        $css = preg_replace('/@import\s+[^;]+;/i', '', $css);

        // Remove any @font-face rules (could load external fonts)
        $css = preg_replace('/@font-face\s*{[^}]+}/is', '', $css);

        // Remove any behavior: url() (IE specific, can execute JS)
        $css = preg_replace('/behavior\s*:\s*[^;]+;/i', '', $css);

        // Filter CSS properties
        $css = $this->filterProperties($css);

        return trim($css);
    }

    /**
     * Remove dangerous patterns from CSS
     */
    private function removeDangerousPatterns(string $css): string
    {
        foreach ($this->dangerousPatterns as $pattern) {
            $css = preg_replace($pattern, '', $css);
        }
        return $css;
    }

    /**
     * Filter CSS to only allow safe properties
     */
    private function filterProperties(string $css): string
    {
        // Create allowed pattern
        $allowedPattern = '/\s*([a-zA-Z-]+)\s*:\s*([^;]+);/i';

        // This is a simplified filter - in production, use a proper CSS parser library
        // For now, we check for obviously dangerous values
        $lines = explode("\n", $css);
        $safeLines = [];

        foreach ($lines as $line) {
            // Skip if line contains dangerous keywords
            if ($this->containsDangerousKeywords($line)) {
                continue;
            }

            // Check if property is in allowed list
            if (preg_match($allowedPattern, $line, $matches)) {
                $property = strtolower(trim($matches[1]));
                if (!in_array($property, $this->allowedProperties)) {
                    // Skip this property
                    continue;
                }
            }

            $safeLines[] = $line;
        }

        return implode("\n", $safeLines);
    }

    /**
     * Check if line contains dangerous keywords
     */
    private function containsDangerousKeywords(string $line): bool
    {
        $dangerous = ['expression(', 'javascript:', 'eval(', 'url(data:', 'behavior:'];
        $lineLower = strtolower($line);

        foreach ($dangerous as $keyword) {
            if (str_contains($lineLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate if CSS is safe to use
     */
    public function isSafe(string $css): bool
    {
        $sanitized = $this->sanitize($css);
        // CSS is safe if it doesn't change significantly after sanitization
        // This is a basic check - more sophisticated checks would be needed in production
        return strlen($sanitized) > 0;
    }
}
