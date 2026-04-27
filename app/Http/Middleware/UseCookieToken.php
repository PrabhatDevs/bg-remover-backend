<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UseCookieToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('access_token');
        Log::info('Cookie token:', [$request->cookie('access_token')]);
        if ($token && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        // 🔥 Log AFTER setting
        Log::info('Bearer AFTER:', [$request->bearerToken()]);
        Log::info('Auth header:', [$request->header('Authorization')]);

        return $next($request);
    }

  
}