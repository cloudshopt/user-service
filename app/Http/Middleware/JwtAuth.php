<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtAuth
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = (string) $request->header('Authorization', '');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Missing Bearer token'], 401);
        }

        $token = trim(substr($authHeader, 7));

        try {
            $claims = app(JwtService::class)->decode($token);

            $userId = $claims['sub'] ?? null;
            if (!$userId) {
                return response()->json(['message' => 'Invalid token'], 401);
            }

            $user = User::query()->find($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 401);
            }

            Auth::setUser($user);
            $request->attributes->set('jwt_claims', $claims);

            return $next($request);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }
    }
}