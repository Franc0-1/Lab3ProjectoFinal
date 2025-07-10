<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only redirect in production environment
        if (app()->environment('production') && !$request->secure()) {
            // Check if we're behind a proxy that handles SSL
            if (!$request->header('X-Forwarded-Proto') || $request->header('X-Forwarded-Proto') !== 'https') {
                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        $response = $next($request);

        // Add security headers for HTTPS
        if (app()->environment('production') || config('app.force_https', false)) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->headers->set('Content-Security-Policy', "upgrade-insecure-requests");
        }

        return $response;
    }
}
