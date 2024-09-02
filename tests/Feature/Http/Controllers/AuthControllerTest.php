<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('app.key', $this->faker->password(8, 12));
    }

    public function test_should_login()
    {
        $password = $this->faker->password(8, 12);
        $user = $this->createUser($password);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'expires_at'
            ]);
    }

    public function test_should_not_login()
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 12)
        ]);

        $response->assertStatus(401);
    }
}
