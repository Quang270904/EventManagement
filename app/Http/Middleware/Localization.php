<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        app()->setLocale(session('localization', config('app.locale')));
        // // Get the locale from the first segment of the URL (e.g., 'en' or 'fr')
        // $locale = $request->segment(1);

        // // Check if the locale is valid, otherwise fall back to the default locale
        // if (in_array($locale, ['en', 'fr', 'de', 'es'])) {  // Add your supported locales here
        //     App::setLocale($locale);  // Set the application locale
        // } else {
        //     App::setLocale(config('app.locale'));  // Default locale defined in config/app.php
        // }

        // // Continue processing the request
        return $next($request);
    }
}
