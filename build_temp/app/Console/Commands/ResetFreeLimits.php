<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetFreeLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:reset-free-limits {--dry-run : Show what would be reset without actually resetting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset free user limits (CV and ATS scan limits) monthly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('DRY RUN: Showing what would be reset...');
        } else {
            $this->info('Resetting free user limits...');
        }

        $resetCount = 0;

        try {
            // Get all free users
            $freeUsers = User::where('subscription_status', 'free')->get();

            foreach ($freeUsers as $user) {
                $oldCvLimit = $user->free_cv_limit;
                $oldAtsLimit = $user->free_ats_limit;

                if (!$isDryRun) {
                    $user->update([
                        'free_cv_limit' => 3,
                        'free_ats_limit' => 3
                    ]);
                }

                $resetCount++;

                $this->line("User ID: {$user->id} ({$user->email}) - CV: {$oldCvLimit} → 3, ATS: {$oldAtsLimit} → 3" .
                    ($isDryRun ? " [DRY RUN]" : " [RESET]"));
            }

            $this->info($isDryRun ? 'Dry run completed!' : 'Free limits reset completed!');
            $this->info("Users processed: {$resetCount}");

            Log::info('Free limits reset completed', [
                'users_reset' => $resetCount,
                'dry_run' => $isDryRun
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error resetting free limits: " . $e->getMessage());
            Log::error('Free limits reset failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Command::FAILURE;
        }
    }
}
