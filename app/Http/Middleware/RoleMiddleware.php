<?php

namespace App\Http\Middleware;

use App\Http\Response\JsonResponse;
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
                return JsonResponse::respondFail('Unauthenticated', 401);
            }

            if (!empty($roles) && !$user->roles->pluck('name')->intersect($roles)->count()) {
                return JsonResponse::respondErrorForbidden('Forbidden');
            }
        } catch (JWTException $e) {
            return JsonResponse::respondFail('Token is missing or invalid', 401);
        }

        return $next($request);
    }
}
