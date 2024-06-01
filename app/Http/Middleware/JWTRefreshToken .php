<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTRefreshToken
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        try {
            if ($request->user()) {
                $token = JWTAuth::fromUser($request->user());
                $response->headers->set('Authorization', 'Bearer ' . $token);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not refresh token'], 500)->header('Content-Type', 'text/plain');
        }

        return $response;
    }

    // public function handle($request, Closure $next)
    // {
    //     $response = $next($request);

    //     if ($request->user()) {
    //         $token = JWTAuth::fromUser($request->user());
    //         $response->headers->set('Authorization', 'Bearer ' . $token);
    //     }

    //     return $response;
    // }
}
