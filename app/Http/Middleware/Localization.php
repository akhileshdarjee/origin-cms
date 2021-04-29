<?php

namespace App\Http\Middleware;

use File;
use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            session()->put('locale', auth()->user()->locale);
        }

        if (session()->get('locale')) {
            app()->setLocale(session()->get('locale'));
        }

        if (!session()->get('translations')) {
            $locale = app()->getLocale();
            $translations = [];

            if ($locale != 'en' && File::exists(resource_path('lang/' . $locale . '.json'))) {
                $translations = File::get(resource_path('lang/' . $locale . '.json'));
                $translations = json_decode($translations, false, 512, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            }

            session()->put('translations', $translations);
        }

        return $next($request);
    }
}
