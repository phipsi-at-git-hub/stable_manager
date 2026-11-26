<?php
// app/Http/Controllers/LocaleController.php 

declare(strict_types=1);

namespace App\Http\Controllers\Localization;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * LocaleController
 */
final class LocaleController
{
    /**
     * update
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => [
                'required',
                'string',
                Rule::in(config('localization.available_locales', [])),
            ],
        ]);

        $request->session()->put('locale', $validated['locale']);

        return back();
    }
}
