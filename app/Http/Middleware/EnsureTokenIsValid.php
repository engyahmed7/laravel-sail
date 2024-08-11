<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // if (!$request->bearerToken()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Token is missing.',
        //     ], 401);
        // }

        if (!Auth::guard('api')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please log in first.',
            ], 401);
        }

        return $next($request);
    }
}

