<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Production Domain Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration ensures that all URLs are correctly generated
    | for the production environment, especially when behind a proxy.
    |
    */

    'force_https' => env('APP_FORCE_HTTPS', env('APP_ENV') === 'production'),
    'trusted_proxies' => ['*'],
    'secure_cookies' => env('SESSION_SECURE_COOKIE', env('APP_ENV') === 'production'),
    'asset_url' => env('ASSET_URL', env('APP_URL')),
];
