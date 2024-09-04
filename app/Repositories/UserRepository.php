<?php

namespace App\Repositories;

use App\Enums\Frequency;
use App\Enums\Period;
use App\Enums\Platform;
use App\Enums\Rawg\RawgGenre;
use App\Enums\Scope;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->scopes = [Scope::Default->value];
            $user->save();

            $this->createDefaultSettings($user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $user;
    }

    public function createRoot(): bool
    {
        $user = User::where('email', config('auth.root.email'))->first();

        if ($user) {
            return false;
        }

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = config('auth.root.name');
            $user->email = config('auth.root.email');
            $user->password = bcrypt(config('auth.root.password'));
            $user->scopes = Scope::values();
            $user->save();

            $this->createDefaultSettings($user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return true;
    }

    private function createDefaultSettings(User $user): void
    {
        $user->settings()->create([
            'platforms' => Platform::values(),
            'genres'    => RawgGenre::values(),
            'period'    => Period::Month->value,
            'frequency' => Frequency::Monthly->value
        ]);

        $user->load('settings');
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

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateSettings(User $user, array $data): User
    {
        $user->settings()->update($data);
        $user->refresh();

        return $user;
    }
}
