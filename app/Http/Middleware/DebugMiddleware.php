<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DebugMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('DebugMiddleware: Ruta solicitada: ' . $request->path());
        Log::info('DebugMiddleware: Usuario autenticado: ' . (Auth::check() ? 'Sí' : 'No'));
        
        if (Auth::check()) {
            Log::info('DebugMiddleware: Usuario ID: ' . Auth::id());
        }
        
        return $next($request);
    }
}
