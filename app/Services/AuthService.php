<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidScopeException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;

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

    /**
     * @param [string] ...$scopes
     * @return boolean
     */
    public function checkScopes(...$scopes): bool
    {
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationException();
        }

        foreach ($scopes as $scope) {
            if (!in_array($scope, $user->scopes)) {
                throw new InvalidScopeException();
            }
        }

        return true;
    }
}
