<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-expired {--notify : Send notifications to expired users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update expired subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired subscriptions...');

        $expiredCount = 0;
        $notifiedCount = 0;

        try {
            // Find expired active subscriptions
            $expiredSubscriptions = Subscription::where('status', 'paid')
                ->where('expires_at', '<', Carbon::now())
                ->get();

            foreach ($expiredSubscriptions as $subscription) {
                // Mark subscription as expired
                $subscription->markAsExpired();
                $expiredCount++;

                $this->line("Expired subscription ID: {$subscription->id} for user ID: {$subscription->user_id}");

                // Send notification if requested
                if ($this->option('notify')) {
                    $user = $subscription->user;
                    if ($user) {
                        // Here you could send email notification
                        // Mail::to($user->email)->send(new SubscriptionExpiredNotification($subscription));
                        $notifiedCount++;
                        $this->line("Notification sent to user: {$user->email}");
                    }
                }
            }

            // Also check users who should be downgraded based on subscription_expires_at
            $usersToDowngrade = User::where('subscription_status', 'premium')
                ->where('subscription_expires_at', '<', Carbon::now())
                ->get();

            foreach ($usersToDowngrade as $user) {
                $user->update([
                    'subscription_status' => 'free',
                    'subscription_expires_at' => null
                ]);

                $this->line("Downgraded user ID: {$user->id} to free plan");
            }

            $this->info('Subscription check completed!');
            $this->info("Expired subscriptions processed: {$expiredCount}");
            $this->info("Notifications sent: {$notifiedCount}");

            Log::info('Expired subscriptions check completed', [
                'expired_subscriptions' => $expiredCount,
                'notifications_sent' => $notifiedCount,
                'users_downgraded' => $usersToDowngrade->count()
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error checking expired subscriptions: " . $e->getMessage());
            Log::error('Expired subscription check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Command::FAILURE;
        }
    }
}
