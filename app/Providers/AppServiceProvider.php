<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use App\Http\Socialite\XelenicProvider;

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
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('xelenic', function ($app) use ($socialite) {
            $config = $app['config']['services.xelenic'];
            return $socialite->buildProvider(XelenicProvider::class, $config);
        });
    }
}
