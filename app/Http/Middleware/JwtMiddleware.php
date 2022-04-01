<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Http\Request;

class JwtMiddleware
{

    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

        } catch(Exception $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['message' => 'Token is invalid']);
            }
            else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['message' => 'Token is expired']);
            } else {
                return response()->json(['message' => 'Authorization token not found']);
            }
        }

        if ($user && in_array($user->role, $roles)) {
        return $next($request);
        }

        return response()->json(['message' => 'You are not authorized to access this route']);
    }
}
