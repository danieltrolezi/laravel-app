<?php

namespace App\Repositories;

use App\Enums\Frequency;
use App\Enums\Period;
use App\Enums\Platform;
use App\Enums\Rawg\RawgGenre;
use App\Enums\Scope;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;
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

            $this->createSettings($user);

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

            $this->createSettings($user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return true;
    }

    /**
     * @param array $data
     * @param array $settings
     * @return User
     */
    public function createFromDiscord(array $data, array $settings): User
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->scopes = [Scope::Default->value];
            $user->discord_user_id = $data['discord_user_id'];
            $user->save();

            $this->createSettings($user, $settings);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $user;
    }

    /**
     * @param string $discordUserId
     * @return User|null
     */
    public function findByDiscordId(string $discordUserId): ?User
    {
        return User::where('discord_user_id', $discordUserId)->first();
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

    private function createSettings(User $user, array $settings = []): void
    {
        $user->settings()->create([
            'platforms' => Arr::get($settings, 'platforms', Platform::values()),
            'genres'    => Arr::get($settings, 'genres', RawgGenre::values()),
            'period'    => Arr::get($settings, 'period', Period::Month->value),
            'frequency' => Arr::get($settings, 'frequency', Frequency::Monthly->value),
        ]);

        $user->load('settings');
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
