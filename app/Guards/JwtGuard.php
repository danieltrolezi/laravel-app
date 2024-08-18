<?php

namespace App\Guards;

use App\Services\AuthService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    protected ?Authenticatable $user = null;
    protected AuthService $authService;

    public function __construct(
        protected UserProvider $provider,
        protected Request $request
    ) {
        $this->authService = resolve(AuthService::class);
    }

    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->user = $user;
            return true;
        }

        return false;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();

        if (empty($token)) {
            return null;
        }

        try {
            $jwt = $this->authService->decodeJWT($token);
            $this->user = $this->provider->retrieveById($jwt->sub);
        } catch (\Exception $e) {
            return null;
        }

        return $this->user;
    }

    public function id()
    {
        return $this->user()?->getAuthIdentifier();
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    public function hasUser()
    {
        return !is_null($this->user);
    }
}
