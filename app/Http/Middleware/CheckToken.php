<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($token = $request->bearerToken()) {
            $accessToken = PersonalAccessToken::findToken($token);
            if (!empty($accessToken)) {
                $accessToken->forceFill(['last_used_at' => now()])->save();
                return $next($request);
            }
        }
        return response()->json(['errors'=>'Token invalido']);
    }
}
