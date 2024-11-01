<?php

namespace App\Http\Middleware\web;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1); // Get the first segment of the URL
        $availableLocales = ['en', 'fr']; // Define your available locales

        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
        } else {
            $locale = Session::get('locale', config('app.locale'));
        }

        App::setLocale($locale);

        return $next($request);
    }
}
