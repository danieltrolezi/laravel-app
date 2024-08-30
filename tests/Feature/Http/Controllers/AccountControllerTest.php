<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\Platform;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function logUserIn()
    {
        $user = User::first();
        Auth::setUser($user);
        return $user;
    }

    public function test_should_return_authenticated_user()
    {
        $user = $this->logUserIn();
        $response = $this->get('/api/account/show');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at',
            'scopes',
            'created_at',
            'updated_at',
            'settings'
        ]);

        $response->assertJsonFragment([
            'id' => $user->id
        ]);
    }

    public function test_should_register_user()
    {
        $response = $this->post('/api/account/register', [
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'scopes',
            'created_at',
            'updated_at',
            'settings'
        ]);
    }

    public function test_should_update_user()
    {
        $user = $this->logUserIn();
        $response = $this->put('/api/account/update', [
            'name' => 'Test User Updated',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'scopes',
            'created_at',
            'updated_at',
            'settings'
        ]);

        $response->assertJson([
            'name' => 'Test User Updated'
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'Test User Updated'
        ]);
    }

    public function test_should_updated_user_settings()
    {
        $user = $this->logUserIn();
        $platforms = [Platform::PC->value];
        $response = $this->put('/api/account/settings', [
            'platforms' => $platforms
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'scopes',
            'created_at',
            'updated_at',
            'settings'
        ]);

        $response->assertJson([
            'settings' => [
                'platforms' => $platforms
            ]
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id'       => $user->id,
            'platforms' => json_encode([Platform::PC->value])
        ]);
    }
}
