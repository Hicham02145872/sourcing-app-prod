<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Supported locales for public website navigation.
     *
     * @var array<int, string>
     */
    private array $supportedLocales = ['en', 'fr', 'ar'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $urlLocale = $request->segment(1);

        if ($urlLocale && in_array($urlLocale, $this->supportedLocales, true)) {
            App::setLocale($urlLocale);
            Session::put('locale', $urlLocale);

            return $next($request);
        }

        if (Session::has('locale') && in_array(Session::get('locale'), $this->supportedLocales, true)) {
            App::setLocale(Session::get('locale'));

            return $next($request);
        }

        $preferredLocale = $request->getPreferredLanguage($this->supportedLocales) ?? config('app.locale', 'en');
        App::setLocale($preferredLocale);
        Session::put('locale', $preferredLocale);

        return $next($request);
    }
}
