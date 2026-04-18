<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $supportedLocales = ['eng', 'fr', 'ar'];

            $locale = $request->segment(1);
            $locale = $locale === 'en' ? 'eng' : $locale;

            if (!in_array($locale, $supportedLocales, true)) {
                $sessionLocale = session('locale');
                $sessionLocale = $sessionLocale === 'en' ? 'eng' : $sessionLocale;
                $locale = in_array($sessionLocale, $supportedLocales, true) ? $sessionLocale : null;
            }

            if (!$locale) {
                $preferredLocale = $request->getPreferredLanguage(['en', 'fr', 'ar']) ?? 'en';
                $locale = $preferredLocale === 'en' ? 'eng' : $preferredLocale;
            }

            return route('login', ['locale' => $locale]);
        });

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'feature' => \App\Http\Middleware\CheckFeatureMiddleware::class,
            'verified.client' => \App\Http\Middleware\RequireVerifiedEmailForClients::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\UpdateSessionActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
