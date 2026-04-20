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

        if ($token && !$request->bearerToken()) {
            Log::info('Found access_token cookie, setting Authorization header');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }

  
}