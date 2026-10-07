<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

return [
    'table_name' => 'email_templates',

    'model' => EmailTemplate::class,

    'app_url' => env('APP_URL', 'http://localhost'),

    'default_settings' => [
        'primary_color' => '#378ADD',
        'bg_color' => '#f8f9fa',
        'font_family' => 'Arial, sans-serif',
        'dark_bg_color' => '#1a1a1a',
        'dark_text_color' => '#e0e0e0',
    ],

    'lock_timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Preview route middleware
    |--------------------------------------------------------------------------
    |
    | The preview/countdown routes are loaded by <iframe> inside the builder,
    | so they must share the panel's session. `auth` (the generic Laravel
    | guard) depends on a `login` named route which Filament panels don't
    | register — so the default is just `web`. To restrict access, append
    | your panel's auth middleware, e.g.:
    |   ['web', \Filament\Http\Middleware\Authenticate::class]
    |
    */

    'preview_route_middleware' => ['web'],

    'storage_disk' => 'public',

    'storage_path' => 'email-images',

    'blocks' => [],
];
