<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rollbar\Rollbar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('services.rollbar.key')) {
            Rollbar::init([
                'access_token' => config('services.rollbar.key'),
                'environment' => config('app.env'),
                'root' => base_path(),
            ], false, false, false);
        }
    }
}
