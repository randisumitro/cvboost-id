<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ResumeController;
use App\Http\Controllers\Web\TemplateController;
use App\Http\Controllers\Web\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'CVBoost.id is working!',
        'timestamp' => now(),
        'templates_count' => \App\Models\Template::count(),
        'database' => config('database.default')
    ]);
});

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/templates', [TemplateController::class, 'gallery'])->name('templates.gallery');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogPost'])->name('blog.post');

// Resume builder
Route::get('/create', [ResumeController::class, 'create'])->name('resume.create');
Route::post('/create/step', [ResumeController::class, 'storeStep'])->middleware('throttle:resume-creation')->name('resume.step.store');
Route::get('/create/previous', [ResumeController::class, 'previousStep'])->name('resume.previous');
Route::get('/create/reset', [ResumeController::class, 'reset'])->name('resume.reset');
Route::get('/resume/{id}/preview', [ResumeController::class, 'preview'])->name('resume.preview');
Route::get('/resume/{id}/ats-score', [ResumeController::class, 'atsScorePage'])->middleware('throttle:ats-score')->name('resume.ats-score');
Route::get('/resume/{id}/download', [ResumeController::class, 'downloadPage'])->middleware('throttle:pdf-download')->name('resume.download');
Route::get('/resume/{id}/html', [ResumeController::class, 'htmlExport'])->middleware('throttle:pdf-download')->name('resume.html');

// Payment
Route::get('/payment/{order_id}', [SubscriptionController::class, 'checkout'])->name('payment.checkout');
Route::get('/payment/success', [SubscriptionController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [SubscriptionController::class, 'failed'])->name('payment.failed');

// Dashboard (authenticated users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resume management
    Route::get('/my-resumes', [ResumeController::class, 'index'])->name('resume.index');
    Route::get('/resume/{id}/edit', [ResumeController::class, 'edit'])->name('resume.edit');
    Route::post('/resume/{id}/duplicate', [ResumeController::class, 'duplicate'])->name('resume.duplicate');
    Route::post('/resume/{id}/switch-template', [ResumeController::class, 'switchTemplate'])->name('resume.template.switch');
    Route::delete('/resume/{id}', [ResumeController::class, 'destroy'])->name('resume.delete');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
});

require __DIR__.'/auth.php';
