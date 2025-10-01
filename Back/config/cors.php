<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | This file configures CORS for your application. Use CORS_ALLOWED_ORIGINS
    | in your .env to list allowed origins (comma-separated) or set FRONTEND_URL.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],

    // Use CORS_ALLOWED_ORIGINS (comma separated) or fallback to FRONTEND_URL
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL', 'http://localhost:5173'))))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // IMPORTANT: true if you need cookies (Sanctum) — do NOT set '*' in allowed_origins if true
    'supports_credentials' => true,

];
