<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Carbon;

class JwtService
{
    public function issueToken(User $user): string
    {
        $now = Carbon::now()->timestamp;
        $ttl = (int) config('jwt.ttl');

        $payload = [
            'iss' => config('jwt.issuer'),
            'aud' => config('jwt.audience'),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttl,
            'sub' => (string) $user->id,
            'email' => $user->email,
        ];

        return JWT::encode($payload, $this->secret(), 'HS256');
    }

    /** @return array<string,mixed> */
    public function decode(string $token): array
    {
        $decoded = JWT::decode($token, new Key($this->secret(), 'HS256'));
        return (array) $decoded;
    }

    private function secret(): string
    {
        $secret = (string) config('jwt.secret');
        if ($secret === '') {
            throw new \RuntimeException('JWT secret is not configured (JWT_SECRET).');
        }
        return $secret;
    }
}