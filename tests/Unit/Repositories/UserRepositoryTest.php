<?php

namespace Tests\Unit\Repositories;

use App\Enums\Frequency;
use App\Enums\Period;
use App\Enums\Platform;
use App\Enums\RawgGenre;
use App\Enums\Scope;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private UserRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = resolve(UserRepository::class);
    }

    public function test_should_create_user(): void
    {
        $user = $this->repository->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertInstanceOf(User::class, $user);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'scopes' => json_encode([Scope::Default->value])
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id'   => $user->id,
            'platforms' => json_encode(Platform::values()),
            'genres'    => json_encode(RawgGenre::values()),
            'period'    => Period::Month->value,
            'frequency' => Frequency::Monthly->value
        ]);
    }

    public function test_should_not_create_more_than_one_root_user()
    {
        $result1 = $this->repository->createRoot();
        $result2 = $this->repository->createRoot();

        $this->assertTrue($result1);
        $this->assertFalse($result2);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_should_update_user(): void
    {
        $user = $this->createUser();

        $result = $this->repository->update($user, [
            'name' => 'Updated User',
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'Updated User',
        ]);

        $this->assertInstanceOf(User::class, $result);
    }

    public function test_should_update_user_settings()
    {
        $user = $this->createUser();
        $platforms = [Platform::PC->value];

        $result = $this->repository->updateSettings($user, [
            'platforms' => $platforms
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id'   => $user->id,
            'platforms' => json_encode($platforms)
        ]);

        $this->assertInstanceOf(User::class, $result);
    }
}
