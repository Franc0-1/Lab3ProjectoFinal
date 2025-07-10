<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class HttpsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Force HTTPS in production or when explicitly configured
        if (app()->environment('production') || config('app.force_https', false)) {
            URL::forceScheme('https');
            
            // Force root URL to HTTPS if APP_URL is set
            if (config('app.url')) {
                $httpsUrl = str_replace('http://', 'https://', config('app.url'));
                URL::forceRootUrl($httpsUrl);
            }
        }
        
        // Trust proxies for platforms like Render, Heroku, etc.
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        }
        
        // Set asset URL if configured
        if (config('app.asset_url')) {
            $httpsAssetUrl = str_replace('http://', 'https://', config('app.asset_url'));
            $this->app['url']->forceRootUrl($httpsAssetUrl);
        }
        
        // Additional security for mixed content
        if (app()->environment('production')) {
            // Force secure cookies
            config(['session.secure' => true]);
            config(['session.http_only' => true]);
            config(['session.same_site' => 'lax']);
        }
    }
}
