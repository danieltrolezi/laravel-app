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
    private const HASH_ALGO = 'HS256';
    private string $key;

    public function __construct()
    {
        $this->key = config('app.key');
    }

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

            $jwt = JWT::encode($payload, $this->key, self::HASH_ALGO);

            return [
                'token'      => $jwt,
                'expires_at' => $payload['exp']
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
        return JWT::decode($token, new Key($this->key, self::HASH_ALGO));
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
