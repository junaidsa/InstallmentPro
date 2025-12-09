<?php

namespace App\Providers;

use App;
use Auth;
use Illuminate\Support\ServiceProvider;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Auth::check()) {
            $locale = Auth::user()->lang ?? 'en'; // Default to English if not set
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
    }
}
