<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\Session;

class SpotifySessionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Session::class, function ($app) {
            return new Session(
                config('services.spotify.client_id'),
                config('services.spotify.client_secret'),
                config('services.spotify.redirect')
            );
        });
    }
}
