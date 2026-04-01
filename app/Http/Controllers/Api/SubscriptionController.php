<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'duration' => 'required|in:monthly,yearly'
        ]);

        // Check if user already has active subscription
        if ($user->isPremium()) {
            return response()->json([
                'error' => 'You already have an active premium subscription.'
            ], 400);
        }

        $amount = $validated['duration'] === 'yearly' ? 490000 : 49000;
        $orderId = 'SUB-' . strtoupper(Str::random(12));

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'duration' => $validated['duration'],
            'status' => 'pending'
        ]);

        // For now, return mock payment URL
        // In production, integrate with Midtrans/Xendit here
        $paymentUrl = route('payment.checkout', $orderId);

        return response()->json([
            'payment_url' => $paymentUrl,
            'order_id' => $orderId,
            'amount' => $amount,
            'duration' => $validated['duration']
        ]);
    }

    public function status(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $activeSubscription = $user->subscriptions()
            ->paid()
            ->where('expires_at', '>', now())
            ->first();

        return response()->json([
            'status' => $user->subscription_status,
            'expires_at' => $user->subscription_expires_at,
            'is_active' => $user->isPremium(),
            'subscription' => $activeSubscription
        ]);
    }

    public function webhook(Request $request)
    {
        // This is for Midtrans/Xendit webhook
        // For now, implement basic webhook handling

        $payload = $request->all();

        // Validate webhook signature here in production

        $orderId = $payload['order_id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$orderId || !$status) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $subscription = Subscription::where('order_id', $orderId)->first();

        if (!$subscription) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        if ($status === 'paid' || $status === 'success') {
            $subscription->markAsPaid($payload['payment_method'] ?? null, $payload);
        } elseif ($status === 'failed') {
            $subscription->update(['status' => 'failed']);
        }

        return response()->json(['success' => true]);
    }

    public function history(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscriptions = $user->subscriptions()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($subscriptions);
    }
}
