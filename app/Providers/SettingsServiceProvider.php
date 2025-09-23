<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\SettingsHelper;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('settings', function ($app) {
            return new SettingsHelper();
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