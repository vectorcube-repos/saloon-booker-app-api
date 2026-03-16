<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Use HTTPS for all generated URLs when the request is secure (fixes mixed-content in admin).
        if (request()->secure() || $this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
