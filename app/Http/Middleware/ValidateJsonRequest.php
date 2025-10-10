<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to validate JSON requests.
 * Ensures API requests have proper Content-Type and Accept headers.
 */
class ValidateJsonRequest
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only validate API routes
        if ($request->is('api/*')) {
            // Check if the request expects JSON
            if (!$request->expectsJson() && !$request->is('api/health')) {
                return response()->json([
                    'error' => 'Bad Request',
                    'message' => 'Request must accept application/json.',
                ], 400);
            }

            // Validate Content-Type for POST, PUT, PATCH requests
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
                $contentType = $request->header('Content-Type');
                
                if ($contentType && !str_contains($contentType, 'application/json')) {
                    return response()->json([
                        'error' => 'Bad Request',
                        'message' => 'Content-Type must be application/json.',
                    ], 400);
                }
            }
        }

        return $next($request);
    }
}
