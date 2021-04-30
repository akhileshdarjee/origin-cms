<?php

namespace App\Providers;

use Blade;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::extend(function($value) {
            return preg_replace('/\@var(.+)/', '<?php ${1}; ?>', $value);
        });

        setlocale(LC_TIME, app()->getLocale(), app()->getLocale() . '.UTF-8');
        Carbon::setLocale(app()->getLocale());
    }
}
