<?php

namespace App\Services;

use App\Enums\Permission;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;

class AuthService
{
    /**
     * @param array $credentials
     * @return string
     */
    public function generateJWT(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $payload = [
                'iss' => env('APP_URL'),
                'sub' => $user->getAuthIdentifier(),
                'scp' => $user->scopes,
                'iat' => time(),
                'exp' => time() + 3600,
            ];

            $jwt = JWT::encode($payload, config('app.key'), 'HS256');

            return [
                'token'      => $jwt,
                'expires_at' => $payload['exp']
            ];
        }

        throw new InvalidCredentialsException();
    }
}
