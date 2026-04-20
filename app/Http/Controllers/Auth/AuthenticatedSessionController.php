<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuthLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $urlLocale = User::normalizeUrlLocale(
            $request->route('locale') ?: $user?->preferred_locale ?: Session::get('locale')
        );
        Session::put('locale', $urlLocale);
        if ($user && $user->preferred_locale !== $urlLocale) {
            $user->update(['preferred_locale' => $urlLocale]);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $welcomeLocale = \App\Models\User::normalizeUrlLocale($user?->preferred_locale);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Log logout
        if ($user) {
            app(AuthLogService::class)->logLogout($user);
        }

        return redirect('/'.$welcomeLocale);
    }
}
