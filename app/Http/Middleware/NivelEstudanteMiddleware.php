<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NivelEstudanteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar autenticado.');
        }

        // Students can only access their own area, but admins and professors can access too
        if (!auth()->user()->isEstudante() && !auth()->user()->isAdmin() && !auth()->user()->isProfessor()) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
