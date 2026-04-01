<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanTempPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resume:clean-temp-pdfs {--hours=24 : Age in hours for files to be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up temporary PDF files older than specified hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);

        $this->info("Cleaning PDF files older than {$hours} hours (before {$cutoffTime})");

        $deletedCount = 0;
        $totalSize = 0;

        try {
            // Get all PDF files in the pdfs directory
            $files = Storage::disk('public')->files('pdfs');

            foreach ($files as $file) {
                // Only process PDF files
                if (!str_ends_with($file, '.pdf')) {
                    continue;
                }

                $lastModified = Storage::disk('public')->lastModified($file);
                $fileSize = Storage::disk('public')->size($file);

                if ($lastModified < $cutoffTime->timestamp) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                    $totalSize += $fileSize;

                    $this->line("Deleted: {$file} (" . $this->formatBytes($fileSize) . ")");
                }
            }

            $this->info("Cleanup completed!");
            $this->info("Files deleted: {$deletedCount}");
            $this->info("Space freed: " . $this->formatBytes($totalSize));

            Log::info('Temporary PDF cleanup completed', [
                'deleted_files' => $deletedCount,
                'space_freed' => $totalSize,
                'hours_threshold' => $hours
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error during cleanup: " . $e->getMessage());
            Log::error('PDF cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
