<?php

namespace Tests\Units\Guards;

use App\Guards\JwtGuard;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class JwtGuardTest extends TestCase
{
    use DatabaseMigrations;

    private JwtGuard $guard;

    public function setUp(): void
    {
        parent::setUp();

        $this->guard = new JwtGuard(
            Auth::createUserProvider('users'),
            request()
        );
    }

    public function test_should_attempt_succeed()
    {
        $password = $this->faker->text(12);

        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $this->assertTrue(
            $this->guard->attempt([
                'email'    => $user->email,
                'password' => $password
            ])
        );
    }

    public function test_should_attempt_fail()
    {
        $user = User::factory()->create([
            'password' => bcrypt($this->faker->text(12))
        ]);

        $this->assertFalse(
            $this->guard->attempt([
                'email'    => $user->email,
                'password' => $this->faker->text(12)
            ])
        );
    }
}
