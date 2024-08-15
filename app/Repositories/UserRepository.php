<?php

namespace App\Repositories;

use App\Enums\Scope;
use App\Models\User;

class UserRepository
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->scopes = [Scope::Default->value];
        $user->save();

        return $user;
    }

    public function createRoot(): bool
    {
        $user = User::where('email', config('auth.root.email'))->first();

        if (!$user) {
            $user = new User();
            $user->name = config('auth.root.name');
            $user->email = config('auth.root.email');
            $user->password = bcrypt(config('auth.root.password'));
            $user->scopes = Scope::values();
            $user->save();

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $user;
    }
}
