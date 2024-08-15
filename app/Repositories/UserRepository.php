<?php

namespace App\Repositories;

use App\Enums\Permission;
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
        $user->scopes = json_encode([Permission::Default->value]);
        $user->save();

        return $user;
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
