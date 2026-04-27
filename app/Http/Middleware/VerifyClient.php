<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyClient
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('X-App-Client') !== 'bgremover-frontend-v1') {
            return response()->json([
                'message' => 'Unauthorized client'
            ], 403);
        }

        return $next($request);
    }
}
