<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    protected ?Authenticatable $user = null;

    public function __construct(
        protected UserProvider $provider,
        protected Request $request
    ) {
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
            $jwt = JWT::decode($token, new Key(config('app.key'), 'HS256'));
            $this->user = $this->provider->retrieveById($jwt->sub);
        } catch (\Exception $e) {
            return null;
        }

        return $this->user;
    }

    public function id()
    {
        if ($this->hasUser()) {
            return $this->user->getAuthIdentifier();
        }

        return null;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser(Authenticatable $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function hasUser()
    {
        return !is_null($this->user);
    }
}
