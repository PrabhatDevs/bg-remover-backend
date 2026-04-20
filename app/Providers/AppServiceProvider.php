<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use RateLimiter;


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
        RateLimiter::for('remove-bg', function (Request $request) {
            // 1. If the user is logged in, give them UNLIMITED access
            if ($request->user()) {
                return Limit::none();
            }

            // 2. If it's a guest, restrict them to 3 requests per minute (1 every 20s)
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
