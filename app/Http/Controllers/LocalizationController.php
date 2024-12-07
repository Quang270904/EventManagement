<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function __invoke($locale)
    {
        if (!in_array($locale, config('localization.locales'))) {
            abort(400);
        }

        session(['localization' => $locale]);

        app()->setLocale($locale);

        return redirect()->back();
    }
}
