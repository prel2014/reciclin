<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        // Verificar si es admin o administrador (compatibilidad)
        if (!in_array(Auth::user()->tipo, ['admin', 'administrador'])) {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
