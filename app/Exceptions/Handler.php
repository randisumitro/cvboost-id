<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Not found',
                    'message' => 'The requested resource does not exist.'
                ], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You do not have permission to access this resource.'
                ], 403);
            }
            return response()->view('errors.403', [], 403);
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Validation failed',
                    'message' => 'The provided data is invalid.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        });

        $this->renderable(function (QueryException $e, $request) {
            // Log the actual error for debugging
            \Log::error('Database error', [
                'message' => $e->getMessage(),
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);

            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Server error',
                    'message' => 'An error occurred while processing your request.'
                ], 500);
            }
            return response()->view('errors.500', [], 500);
        });

        $this->renderable(function (Throwable $e, $request) {
            // Log unexpected errors
            \Log::error('Unexpected error', [
                'message' => $e->getMessage(),
                'class' => get_class($e),
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);

            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Server error',
                    'message' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.'
                ], 500);
            }
            return response()->view('errors.500', ['message' => config('app.debug') ? $e->getMessage() : null], 500);
        });
    }
}
