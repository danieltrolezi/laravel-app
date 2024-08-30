<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidScopeException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\AuthenticationException;
use stdClass;

class AuthService
{
    private string $key;
    private string $algorithm;
    private int $ttl;

    public function __construct()
    {
        $this->key = config('app.key');
        $this->algorithm = config('auth.jwt.algorithm');
        $this->ttl = config('auth.jwt.ttl');
    }

    /**
     * @param array $credentials
     * @return array
     */
    public function generateJWT(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $payload = [
                'iss' => env('APP_URL'),
                'sub' => $user->getAuthIdentifier(),
                'iat' => time()
            ];

            if (config('auth.jwt.expires')) {
                $payload['exp'] = time() + $this->ttl;
            }

            $jwt = JWT::encode($payload, $this->key, $this->algorithm);

            return [
                'token'      => $jwt,
                'expires_at' => isset($payload['exp']) ? $payload['exp'] : 'never'
            ];
        }

        throw new InvalidCredentialsException();
    }

    /**
     * @param string $token
     * @return stdClass
     */
    public function decodeJWT(string $token): stdClass
    {
        return JWT::decode($token, new Key($this->key, $this->algorithm));
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
