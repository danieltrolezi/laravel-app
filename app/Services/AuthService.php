<?php

namespace App\Services;

use App\Enums\Permission;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * @param array $credentials
     * @return string
     */
    public function getAccessToken(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->tokens()->delete();
            $permissions = [Permission::Default->value];

            if ($user->email === config('auth.root.email')) {
                $permissions = Permission::values();
            }

            $token = $user->createToken(
                'default',
                $permissions,
                now()->addMinutes(config('sanctum.expiration'))
            );

            return [
                'token'      => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at->format('U')
            ];
        }

        throw new InvalidCredentialsException();
    }
}
