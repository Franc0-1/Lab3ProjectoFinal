<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        Log::info('RedirectIfAuthenticated: Procesando ruta: ' . $request->path());
        
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            Log::info('RedirectIfAuthenticated: Verificando guard: ' . ($guard ?? 'default'));
            
            if (Auth::guard($guard)->check()) {
                Log::info('RedirectIfAuthenticated: Usuario autenticado encontrado, redirigiendo a /');
                return redirect('/');
            }
        }

        Log::info('RedirectIfAuthenticated: No hay usuario autenticado, continuando');
        return $next($request);
    }
}
