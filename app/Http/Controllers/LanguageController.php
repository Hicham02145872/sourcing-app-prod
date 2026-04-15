<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        $supported = ['en', 'fr', 'ar'];
        if (! in_array($locale, $supported, true)) {
            $locale = 'en';
        }

        Session::put('locale', $locale);

        $previousUrl = url()->previous();
        $path = parse_url($previousUrl, PHP_URL_PATH) ?? '/';
        $query = parse_url($previousUrl, PHP_URL_QUERY);
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        if (! empty($segments) && in_array($segments[0], $supported, true)) {
            $segments[0] = $locale;
            $newPath = '/' . implode('/', $segments);

            if ($query) {
                $newPath .= '?' . $query;
            }

            return Redirect::to($newPath);
        }

        return Redirect::back();
    }
}
