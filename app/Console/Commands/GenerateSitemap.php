<?php

namespace App\Console\Commands;

use App\Models\Template;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for SEO';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        try {
            $baseUrl = config('app.url');
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            // Static pages
            $staticPages = [
                '/' => '1.0',
                '/templates' => '0.9',
                '/pricing' => '0.8',
                '/blog' => '0.8',
                '/create' => '0.7',
                '/login' => '0.5',
                '/register' => '0.5',
            ];

            foreach ($staticPages as $url => $priority) {
                $sitemap .= $this->generateUrlNode($baseUrl . $url, $priority);
            }

            // Template pages
            $templates = Template::active()->get();
            foreach ($templates as $template) {
                $templateUrl = $baseUrl . '/templates/' . $template->slug;
                $sitemap .= $this->generateUrlNode($templateUrl, '0.8');
            }

            // Blog posts
            $blogPosts = [
                'tips-resume-pass-ats' => '0.7',
                'write-professional-summary' => '0.7',
                'resume-formatting-guide' => '0.6',
                'common-resume-mistakes' => '0.6',
                'tailor-resume-job-application' => '0.6',
            ];

            foreach ($blogPosts as $slug => $priority) {
                $blogUrl = $baseUrl . '/blog/' . $slug;
                $sitemap .= $this->generateUrlNode($blogUrl, $priority);
            }

            $sitemap .= '</urlset>';

            // Save sitemap to public directory
            Storage::disk('public')->put('sitemap.xml', $sitemap);

            $this->info('Sitemap generated successfully!');
            $this->info('Total URLs: ' . (count($staticPages) + $templates->count() + count($blogPosts)));
            $this->info('Sitemap available at: ' . $baseUrl . '/sitemap.xml');

            Log::info('Sitemap generated successfully', [
                'total_urls' => count($staticPages) + $templates->count() + count($blogPosts),
                'static_pages' => count($staticPages),
                'templates' => $templates->count(),
                'blog_posts' => count($blogPosts)
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error generating sitemap: " . $e->getMessage());
            Log::error('Sitemap generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Generate URL node for sitemap
     */
    private function generateUrlNode($url, $priority)
    {
        $lastmod = now()->format('Y-m-d');
        $changefreq = $priority >= 0.9 ? 'daily' : ($priority >= 0.7 ? 'weekly' : 'monthly');

        return "  <url>\n" .
               "    <loc>{$url}</loc>\n" .
               "    <lastmod>{$lastmod}</lastmod>\n" .
               "    <changefreq>{$changefreq}</changefreq>\n" .
               "    <priority>{$priority}</priority>\n" .
               "  </url>\n";
    }
}
