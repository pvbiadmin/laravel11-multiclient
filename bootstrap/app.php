<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['role' => App\Http\Middleware\Role::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Global exception logger
        $exceptions->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                Log::error($e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => Auth::id(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ]);
            }
        });

        // Handle TokenMismatchException (419 Page Expired)
        $exceptions->renderable(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session has expired. Please refresh the page.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'message' => 'Your session has expired. Please log in again.',
            ]);
        });

        // Handle ValidationException
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        // Handle AuthenticationException
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->guest(route('login'));
        });

        // Handle ModelNotFoundException and NotFoundHttpException
        $exceptions->renderable(function (NotFoundHttpException|ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found.',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->view('errors.404', [], Response::HTTP_NOT_FOUND);
        });

        // Handle MethodNotAllowedHttpException
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Method not allowed.',
                ], Response::HTTP_METHOD_NOT_ALLOWED);
            }
        });

        // Handle Database Errors
        $exceptions->renderable(function (QueryException $e, $request) {
            Log::error('Database error: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'An internal server error occurred.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->view('errors.500', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        // Handle Rate Limiting
        $exceptions->renderable(function (TooManyRequestsHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Too many requests.',
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            return response()->view('errors.429', [
                'retryAfter' => $e->getHeaders()['Retry-After'] ?? null,
            ], Response::HTTP_TOO_MANY_REQUESTS);
        });
    })->create();
