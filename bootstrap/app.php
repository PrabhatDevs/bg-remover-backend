<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ❌ REMOVE — only needed for cookie/session based auth
        // $middleware->web(append: [
        //     \Illuminate\Cookie\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        // ]);
    
        // ❌ REMOVE — CSRF not needed for API + Bearer token
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        // ❌ REMOVE — your custom cookie middleware (not needed anymore)
        $middleware->alias([
            // 'cookie.token' => \App\Http\Middleware\UseCookieToken::class,
            'optional.auth' => \App\Http\Middleware\OptionalAuth::class,
            'verify.client' => \App\Http\Middleware\VerifyClient::class,
        ]);

        // ❌ REMOVE — no need to inject cookie → bearer anymore
        // $middleware->api(prepend: [
        //     \Illuminate\Http\Middleware\HandleCors::class,
        //     \App\Http\Middleware\UseCookieToken::class,
        // ]);
    
        // ✅ OPTIONAL (keep if you want explicit CORS handling)
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();
