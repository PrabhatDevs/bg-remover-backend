<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is logged in but email is NOT verified
        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Your email address is not verified.',
                'must_verify' => true // Your React frontend can check this key
            ], 403);
        }

        return $next($request);
    }
}
