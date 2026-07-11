<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DevLoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.dev-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        $email = $request->input('email');
        $password = $request->input('password');
        $ip = $request->ip();

        if (!Auth::attempt(['email' => $email, 'password' => $password], false)) {
            RateLimiter::hit($this->throttleKey($request), 60);

            app(AuthLogService::class)->logLogin(null, false, $email, $ip);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (!$user->isDeveloper()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('dev-login.unauthorized'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        app(AuthLogService::class)->logLogin($user, true, $email, $ip);

        $locale = User::normalizeUrlLocale(session()->get('locale', $user->preferred_locale ?? 'eng'));
        session()->put('locale', $locale);

        return redirect()->route('admin.dev-dashboard');
    }

    private function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(Request $request): string
    {
        return 'dev_login:' . Str::transliterate(Str::lower($request->string('email'))) . '|' . $request->ip();
    }
}
