<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application locale
    |--------------------------------------------------------------------------
    |
    | Locale identifiers use BCP 47 casing (language lower-case, region
    | upper-case), for example en-US and de-DE.
    |
    */
    'default_locale' => env('APP_LOCALE', 'en-US'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en-US'),

    /*
    |--------------------------------------------------------------------------
    | Available locales
    |--------------------------------------------------------------------------
    */
    'available_locales' => [
        'en-US',
        'de-DE',
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation files
    |--------------------------------------------------------------------------
    */
    'translations_path' => base_path('locales'),
];
