<?php
// src/Core/Localization/Middleware/HandleInertiaRequests.php

declare(strict_types=1);

namespace App\Core\Localization\Middleware;

use App\Core\Localization\Localization;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        $localization = app(Localization::class);

        return array_merge(parent::share($request), [
            'localization' => [
                'locale' => $localization->locale(),
                'fallbackLocale' => $localization->fallbackLocale(),
                'messages' => $localization->allLocales(),
            ],
        ]);
    }
}
