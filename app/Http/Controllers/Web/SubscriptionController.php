<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeSubscription = $user->subscriptions()
            ->paid()
            ->where('expires_at', '>', now())
            ->first();

        return view('subscription.index', compact('activeSubscription'));
    }

    public function checkout($orderId)
    {
        $subscription = Subscription::where('order_id', $orderId)->firstOrFail();

        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('subscription.checkout', compact('subscription'));
    }

    public function success()
    {
        return view('subscription.success');
    }

    public function failed()
    {
        return view('subscription.failed');
    }
}
