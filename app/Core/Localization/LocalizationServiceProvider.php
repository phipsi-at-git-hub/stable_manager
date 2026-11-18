<?php
// app/Core/Localization/LocalizationServiceProvider.php

declare(strict_types=1);

namespace App\Core\Localization;

use Illuminate\Support\ServiceProvider;

final class LocalizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /*
         * A scoped binding gives each request/job its own locale-aware
         * instance and avoids leaking locale state in long-lived workers.
         */
        $this->app->scoped(Localization::class, function ($app): Localization {
            return new Localization(
                locale: $app->getLocale(),
                fallbackLocale: (string) config(
                    'localization.fallback_locale', 
                    'en-US')
                ,
                translationsPath: (string) config(
                    'localization.translations_path', 
                    base_path('locales')
                ),
                availableLocales: (array) config(
                    'localization.available_locales', 
                    ['en-US'],
                ),
            );
        });
    }
}
