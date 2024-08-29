<?php

namespace Tests\Unit\Guards;

use App\Guards\JwtGuard;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Mockery;
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

    public function test_attempt_should_succeed()
    {
        $password = $this->faker->password(8, 12);
        $user = $this->createUser($password);

        $this->assertTrue(
            $this->guard->attempt([
                'email'    => $user->email,
                'password' => $password
            ])
        );
    }

    public function test_attempt_should_fail()
    {
        $user = $this->createUser();

        $this->assertFalse(
            $this->guard->attempt([
                'email'    => $user->email,
                'password' => $this->faker->password(8, 12)
            ])
        );
    }

    public function test_check_should_return_true()
    {
        $user = $this->createUser();
        $this->guard->setUser($user);

        $this->assertTrue(
            $this->guard->check()
        );
    }

    public function test_check_should_return_false()
    {
        $this->assertFalse(
            $this->guard->check()
        );
    }

    public function test_guest_should_return_true()
    {
        $this->assertTrue(
            $this->guard->guest()
        );
    }

    public function test_guest_should_return_false()
    {
        $user = $this->createUser();
        $this->guard->setUser($user);

        $this->assertFalse(
            $this->guard->guest()
        );
    }

    public function test_user_should_return_null()
    {
        $this->assertNull(
            $this->guard->user()
        );
    }

    public function test_user_should_return_user()
    {
        $user = $this->createUser();
        $this->guard->setUser($user);

        $this->assertEquals(
            $user->toArray(),
            $this->guard->user()->toArray()
        );
    }

    public function test_user_should_return_user_using_jwt()
    {
        $password = $this->faker->password(8, 12);
        $user = $this->createUser($password);

        $jwt = resolve(AuthService::class)->generateJWT([
            'email'    => $user->email,
            'password' => $password
        ]);

        $request = Mockery::mock(\Illuminate\Http\Request::class)->makePartial();
        $request->shouldReceive('bearerToken')
                ->andReturn($jwt['token']);

        $guard = new JwtGuard(
            Auth::createUserProvider('users'),
            $request
        );

        $this->assertEquals(
            $user->toArray(),
            $guard->user()->toArray()
        );
    }

    public function test_user_should_return_null_invalid_jwt()
    {
        $this->createUser()->save();

        $request = Mockery::mock(\Illuminate\Http\Request::class)->makePartial();
        $request->shouldReceive('bearerToken')
                ->andReturn($this->faker->text(12));

        $guard = new JwtGuard(
            Auth::createUserProvider('users'),
            $request
        );

        $this->assertNull(
            $guard->user()
        );
    }

    public function test_id_should_return_null()
    {
        $this->assertNull(
            $this->guard->id()
        );
    }

    public function test_should_set_user()
    {
        $user = User::factory()->create();

        $this->guard->setUser($user);

        $this->assertEquals(
            $user->id,
            $this->guard->user()->id
        );
    }

    public function test_validate_should_return_false()
    {
        $this->assertFalse(
            $this->guard->validate([])
        );
    }

    public function test_has_user_should_return_false()
    {
        $this->assertFalse(
            $this->guard->hasUser()
        );
    }

    public function test_has_user_should_return_true()
    {
        $user = User::factory()->create();
        $this->guard->setUser($user);

        $this->assertTrue(
            $this->guard->hasUser()
        );
    }
}
