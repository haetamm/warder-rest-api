<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            if (!empty($roles) && !$user->roles->pluck('name')->intersect($roles)->count()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is missing or invalid'], 401);
        }

        return $next($request);
    }
}
