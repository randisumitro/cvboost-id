<?php

use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Resume endpoints with rate limiting
Route::middleware('throttle:api')->group(function () {
    Route::get('/resumes', [ResumeController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/resumes', [ResumeController::class, 'store']);
    Route::get('/resumes/{id}', [ResumeController::class, 'show']);
    Route::put('/resumes/{id}', [ResumeController::class, 'update']);
    Route::post('/resumes/{id}/generate-pdf', [ResumeController::class, 'generatePdf'])->middleware('throttle:pdf-download');
    Route::post('/resumes/{id}/ats-score', [ResumeController::class, 'atsScore'])->middleware('throttle:ats-score');
});

// Template endpoints with rate limiting
Route::middleware('throttle:api')->group(function () {
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::get('/templates/{id}', [TemplateController::class, 'show']);
    Route::get('/templates/{id}/preview', [TemplateController::class, 'preview']);
});

// Subscription endpoints with rate limiting
Route::middleware('throttle:api')->group(function () {
    Route::get('/subscription/status', [SubscriptionController::class, 'status'])->middleware('auth:sanctum');
    Route::post('/subscription/create', [SubscriptionController::class, 'create'])->middleware('auth:sanctum');
    Route::post('/subscription/webhook/midtrans', [SubscriptionController::class, 'webhook']);
    Route::get('/subscription/history', [SubscriptionController::class, 'history'])->middleware('auth:sanctum');
});

// Tracking endpoints
Route::post('/track/view', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'page_url' => 'required|string',
        'resume_id' => 'nullable|integer|exists:resumes,id'
    ]);

    \App\Models\PageView::create([
        'resume_id' => $validated['resume_id'] ?? null,
        'page_url' => $validated['page_url'],
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);

    return response()->json(['success' => true]);
});

Route::post('/track/ad-impression', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'ad_type' => 'required|string|in:banner,interstitial,video',
        'page_url' => 'required|string'
    ]);

    \App\Models\AdsImpression::trackImpression(
        $validated['ad_type'],
        $validated['page_url'],
        auth()->id()
    );

    return response()->json(['success' => true]);
});
