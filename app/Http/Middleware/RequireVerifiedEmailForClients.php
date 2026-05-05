<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireVerifiedEmailForClients
{
    /**
     * Handle an incoming request.
     *
     * Require email verification only for clients, not for admins or super_admins.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admins and super_admins don't need email verification
        if ($user && ($user->isAdmin() || $user->isSuperAdmin())) {
            return $next($request);
        }

        // Clients must have verified email
        if ($user && $user->isClient() && !$user->hasVerifiedEmail()) {
            $supportedLocales = ['eng', 'fr', 'ar'];

            $locale = $request->segment(1);
            $locale = $locale === 'en' ? 'eng' : $locale;

            if (!in_array($locale, $supportedLocales, true)) {
                $sessionLocale = session('locale');
                $sessionLocale = $sessionLocale === 'en' ? 'eng' : $sessionLocale;
                $locale = in_array($sessionLocale, $supportedLocales, true) ? $sessionLocale : null;
            }

            if (!$locale) {
                $appLocale = app()->getLocale();
                $appLocale = $appLocale === 'en' ? 'eng' : $appLocale;
                $locale = in_array($appLocale, $supportedLocales, true) ? $appLocale : 'eng';
            }

            return redirect()->route('verification.notice', ['locale' => $locale]);
        }

        return $next($request);
    }
}
