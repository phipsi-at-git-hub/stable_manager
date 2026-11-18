<?php
// app/Core/Localization/Middleware/SetLocale.php

declare(strict_types=1);

namespace App\Core\Localization\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
         * User-specific locale selection can be added here later.
         * Until then, the configured application default is authoritative.
         */
        $locale = (string) config('localization.default_locale', 'en-US');
        $availableLocales = (array) config('localization.available_locales', ['en-US']);

        if (! in_array($locale, $availableLocales, true)) {
            $locale = (string) config('localization.fallback_locale', 'en-US');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
