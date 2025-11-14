<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/login',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        \Log::debug('CSRF Check', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'has_token' => $request->has('_token'),
            'session_token' => $request->session()->token(),
            'request_token' => $request->input('_token'),
            'tokens_match' => $request->input('_token') === $request->session()->token(),
        ]);

        return parent::handle($request, $next);
    }
}