<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RawgGamesControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createRootUser();
    }

    private function getGamesJsonStructure(): array
    {
        return [
            'total',
            'page_size',
            'current_page',
            'last_page',
            'next_page_url',
            'prev_page_url',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'background_image',
                    'released',
                    'platforms',
                    'stores',
                    'genres'
                ]
            ]
        ];
    }

    public function test_should_return_recommendations()
    {
        $clientMock = $this->createClientMock('rawg_games.json');
        $this->app->offsetSet(Client::class, $clientMock);

        $res = $this->actingAs($this->user)
                    ->get('/api/rawg/games/recommendations/action?platforms=pc,playstation5');

        $res->assertStatus(200);
        $res->assertJsonStructure($this->getGamesJsonStructure());
    }

    public function test_should_return_validation_error()
    {
        $clientMock = $this->createClientMock('rawg_games.json');
        $this->app->offsetSet(Client::class, $clientMock);

        $res = $this->actingAs($this->user)
                    ->get('/api/rawg/games/recommendations/action?platforms=' . $this->faker->word());

        $res->assertStatus(422);
        $res->assertJsonStructure([
            'message',
            'errors' => [
                'platforms'
            ]
        ]);
    }

    public function test_should_return_upcoming_releases()
    {
        $clientMock = $this->createClientMock('rawg_games.json');
        $this->app->offsetSet(Client::class, $clientMock);

        $res = $this->actingAs($this->user)->get('/api/rawg/games/upcoming-releases/next-7-days');

        $res->assertStatus(200);
        $res->assertJsonStructure($this->getGamesJsonStructure());
    }

    public function test_should_return_achievements()
    {
        $clientMock = $this->createClientMock('rawg_achievements.json');
        $this->app->offsetSet(Client::class, $clientMock);

        $res = $this->actingAs($this->user)->get('/api/rawg/games/' . $this->faker->name . '/achievements');

        $res->assertStatus(200);
        $res->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'image',
                'percent'
            ]
        ]);
    }
}
