<?php
// app/Http/Controllers/Auth/AuthenticationSessionController.php 

namespace App\Http\Controllers\Auth;

use App\Enums\Identity\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Identity\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * create
     * 
     * Show the login page.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Pages/Login');
    }

    /**
     * store
     * 
     * Authenticate the user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:254'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $login = trim($validated['login']);

        $throttleKey = Str::transliterate(
            Str::lower($login).'|'.$request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'login' => 'Zu viele Anmeldeversuche. Bitte versuche es später erneut.',
            ]);
        }

        $user = User::query()
            ->where('username', $login)
            ->orWhereHas('loginEmail', function ($query) use ($login) {
                $query->whereRaw('LOWER(email) = ?', [
                    Str::lower($login),
                ]);
            })
            ->first();

        if (
            !$user ||
            $user->status !== UserStatus::ACTIVE ||
            !Hash::check($validated['password'], $user->password)
        ) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login' => 'Die Anmeldedaten sind ungültig.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('platform.dashboard'));
    }

    /**
     * destroy
     * 
     * Log the user out.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
