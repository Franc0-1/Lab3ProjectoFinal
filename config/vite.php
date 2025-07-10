<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vite Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file is used to configure Vite for your Laravel
    | application. It includes settings for development and production.
    |
    */

    'build_path' => env('VITE_BUILD_PATH', 'build'),
    'manifest_path' => env('VITE_MANIFEST_PATH', 'build/.vite/manifest.json'),
    'hot_file' => env('VITE_HOT_FILE', 'hot'),
    'prefetch_chunks' => env('VITE_PREFETCH_CHUNKS', 3),
    
    /*
    |--------------------------------------------------------------------------
    | Asset URL Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration ensures that assets are served with the correct
    | URL in production environments, especially when behind a proxy.
    |
    */
    
    'asset_url' => env('ASSET_URL', env('APP_URL')),
    'secure_assets' => env('VITE_SECURE_ASSETS', env('APP_ENV') === 'production'),
];
