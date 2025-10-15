<?php

namespace App\Http\Middleware;

use App\Models\AccessLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log the access if user is authenticated
        if (auth()->check()) {
            try {
                AccessLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'page_access',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'description' => 'Acesso à página: ' . $request->path(),
                ]);
            } catch (\Exception $e) {
                // Don't fail the request if logging fails
                \Log::error('Failed to log access: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
