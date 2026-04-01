<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class GeneratePDF implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [5, 10, 30];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $resumeId,
        public bool $watermark = false,
        public ?int $userId = null
    ) {
        $this->onQueue('pdf-generation');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $resume = Resume::with('template')->findOrFail($this->resumeId);

            // Generate PDF
            $pdf = Pdf::loadView('resumes.pdf', [
                'resume' => $resume,
                'template' => $resume->template,
                'watermark' => $this->watermark
            ]);

            // Configure PDF for better quality
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => $resume->font_family,
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
            ]);

            $filename = 'resume_' . $resume->id . '_' . time() . '.pdf';
            $path = 'public/pdfs/' . $filename;

            // Save to storage
            Storage::put($path, $pdf->output());

            // Update resume record
            $resume->incrementDownload();

            // Log successful generation
            Log::info('PDF generated successfully', [
                'resume_id' => $resume->id,
                'filename' => $filename,
                'user_id' => $this->userId
            ]);

            // Optionally notify user (if userId is provided)
            if ($this->userId) {
                $user = User::find($this->userId);
                if ($user) {
                    // Send notification or email
                    Log::info('User notified of PDF generation', [
                        'user_id' => $this->userId,
                        'resume_id' => $resume->id
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'resume_id' => $this->resumeId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception to trigger job retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('PDF generation job failed permanently', [
            'resume_id' => $this->resumeId,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Optionally notify user of failure
        if ($this->userId) {
            $user = User::find($this->userId);
            if ($user) {
                // Send failure notification
                Log::error('User notified of PDF generation failure', [
                    'user_id' => $this->userId,
                    'resume_id' => $this->resumeId
                ]);
            }
        }
    }
}
