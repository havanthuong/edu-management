<?php

namespace App\Http\Middleware;

use App\Models\AccountSession;
use Carbon\Carbon;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthenticate
{

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $lastSession = AccountSession::where('accountId', $user->id)->orderBy('created_at', 'desc')->first();

        if (!$lastSession || Carbon::now()->diffInHours($lastSession->created_at) > 24) {
            return response()->json(['error' => 'Token expired'], 401);
        }

        return $next($request);
    }
}
