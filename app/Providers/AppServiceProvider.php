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

        // 🔐 Auth (login/register)
        RateLimiter::for('auth', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // 📤 Upload (VERY IMPORTANT)
        RateLimiter::for('remove-bg', function ($request) {
            if ($request->user()) {
                return Limit::perMinute(20)->by($request->user()->id);
            }
            return Limit::perMinute(3)->by($request->ip());
        });

        // 🔁 Polling (status check)
        RateLimiter::for('polling', function ($request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // 📥 Download
        RateLimiter::for('download', function ($request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // 🔄 OTP resend
        RateLimiter::for('otp', function ($request) {
            return Limit::perMinute(2)->by($request->ip());
        });
    }
}
