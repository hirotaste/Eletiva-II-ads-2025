<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Global Exception Handler for the application.
 * Provides centralized error handling and consistent error responses.
 */
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
        ResourceNotFoundException::class,
        UnauthorizedException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle ModelNotFoundException
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'The requested resource was not found.',
                ], 404);
            }
        });

        // Handle NotFoundHttpException
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'The requested endpoint was not found.',
                ], 404);
            }
        });

        // Handle AuthenticationException
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'You are not authenticated.',
                ], 401);
            }
        });

        // Handle custom ValidationException
        $this->renderable(function (ValidationException $e, $request) {
            return $e->render();
        });

        // Handle Laravel ValidationException for better formatting
        $this->renderable(function (LaravelValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // For API requests, always return JSON
        if ($request->expectsJson() && !($e instanceof LaravelValidationException)) {
            return $this->renderJsonException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render exception as JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return JsonResponse
     */
    protected function renderJsonException($request, Throwable $e): JsonResponse
    {
        // Handle specific exception types with custom rendering
        if (method_exists($e, 'render')) {
            return $e->render();
        }

        // Default error response
        $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        $message = $e->getMessage() ?: 'An unexpected error occurred.';

        return response()->json([
            'error' => class_basename($e),
            'message' => $message,
        ], $status);
    }
}
