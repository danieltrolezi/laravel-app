<?php

namespace Tests;

use App\Enums\Scope;
use App\Models\Game;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    protected function createUser(?string $password = null)
    {
        $data = !empty($password)
            ? ['password' => bcrypt($password)]
            : [];

        return User::factory()
            ->hasSettings(1)
            ->create($data)
            ->load('settings');
    }

    protected function createRootUser()
    {
        return User::factory()->hasSettings(1)->create([
            'scopes' => Scope::values()
        ])->load('settings');
    }

    protected function createGameCollection(int $total): Collection
    {
        $games = [];

        for ($i = 0; $i < $total; $i++) {
            $games[] = Game::factory()->make();
        }

        return collect($games);
    }

    protected function createClientMock(string $file)
    {
        $contents = file_get_contents(
            storage_path("tests/$file")
        );

        $body = Mockery::mock(Stream::class)->makePartial();
        $body->shouldReceive('getContents')->andReturn($contents);

        $response = Mockery::mock(Response::class)->makePartial();
        $response->shouldReceive('getBody')->andReturn($body);

        $client = Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('request')->andReturn($response);

        return $client;
    }

    protected function mockRedis(): void
    {
        Redis::shouldReceive('get')
        ->once()
        ->andReturn(null);

        Redis::shouldReceive('setEx')
        ->once()
        ->andReturn(true);
    }

    protected function prepRawgForUnitTesting(): void
    {
        Config::set('services.rawg.host', $this->faker->url());
        Config::set('services.rawg.api_key', $this->faker->password(8, 12));

        $this->mockRedis();
    }
}
