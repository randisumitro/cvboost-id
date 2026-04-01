<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Http\Controllers\Api\ResumeController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessATSScan implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [5, 10, 30];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $resumeId,
        public string $jobPosition = 'Software Engineer',
        public ?int $userId = null
    ) {
        $this->onQueue('ats-scanning');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $resume = Resume::findOrFail($this->resumeId);

            // Use the ATS scoring logic from ResumeController
            $controller = new ResumeController();
            $score = $controller->calculateATSScore($resume, $this->jobPosition);
            $feedback = $controller->generateATSFeedback($resume, $this->jobPosition);
            $suggestions = $controller->generateATSSuggestions($resume, $this->jobPosition);

            // Update resume with ATS results
            $resume->update([
                'ats_score' => $score,
                'ats_feedback' => array_merge($feedback, ['suggestions' => $suggestions])
            ]);

            Log::info('ATS scan completed successfully', [
                'resume_id' => $resume->id,
                'score' => $score,
                'job_position' => $this->jobPosition,
                'user_id' => $this->userId
            ]);

        } catch (\Exception $e) {
            Log::error('ATS scan failed', [
                'resume_id' => $this->resumeId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ATS scan job failed permanently', [
            'resume_id' => $this->resumeId,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
