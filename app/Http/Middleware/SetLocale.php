<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Supported locales for public website navigation.
     *
     * @var array<int, string>
     */
    private array $supportedLocales = ['eng', 'fr', 'ar'];

    private function toAppLocale(string $locale): string
    {
        return $locale === 'eng' ? 'en' : $locale;
    }

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
            App::setLocale($this->toAppLocale($urlLocale));
            Carbon::setLocale(App::getLocale());
            Session::put('locale', $urlLocale);
            URL::defaults(['locale' => $urlLocale]);

            return $next($request);
        }

        if (Session::has('locale') && in_array(Session::get('locale'), $this->supportedLocales, true)) {
            $sessionLocale = Session::get('locale');
            App::setLocale($this->toAppLocale($sessionLocale));
            Carbon::setLocale(App::getLocale());
            URL::defaults(['locale' => $sessionLocale]);

            return $next($request);
        }

        $urlLocale = 'eng';
        App::setLocale($this->toAppLocale($urlLocale));
        Carbon::setLocale(App::getLocale());
        Session::put('locale', $urlLocale);
        URL::defaults(['locale' => $urlLocale]);

        return $next($request);
    }
}
