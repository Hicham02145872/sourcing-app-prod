<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Legacy path /verify-email has no {locale} segment, so SetLocale never binds from URL
        // and __() can stay in English. Canonical localized URL fixes translations for resend/logout.
        if ($request->segment(1) === 'verify-email') {
            return redirect()->route('verification.notice', ['locale' => $this->resolveUrlLocale($request)]);
        }

        return view('auth.verify-email');
    }

    /**
     * @return 'eng'|'fr'|'ar'
     */
    private function resolveUrlLocale(Request $request): string
    {
        $supportedLocales = ['eng', 'fr', 'ar'];
        $sessionLocale = session('locale');
        $sessionLocale = $sessionLocale === 'en' ? 'eng' : $sessionLocale;
        if (in_array($sessionLocale, $supportedLocales, true)) {
            return $sessionLocale;
        }

        $user = $request->user();
        if ($user !== null) {
            return User::normalizeUrlLocale($user->preferred_locale ?? null);
        }

        $appLocale = app()->getLocale();
        $appLocale = $appLocale === 'en' ? 'eng' : $appLocale;
        if (in_array($appLocale, $supportedLocales, true)) {
            return $appLocale;
        }

        return 'eng';
    }
}
