<?php

namespace App\Providers;

use App\Services\InstagramAPIService;
use Illuminate\Support\ServiceProvider;

class InstagramBasicProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Services\InstagramAPIService', function ($app) {
            return new InstagramAPIService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
