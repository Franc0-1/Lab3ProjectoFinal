<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
// Verificar si el usuario tiene rol de admin
if ($user->hasRole('admin')) {
    return $next($request);
}

        // Si es cliente, redirigir a la página principal
        return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
