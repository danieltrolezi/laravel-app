<?php

namespace Tests\Unit\Services;

use App\Services\RawgAchievementService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class RawgAchievementServiceTest extends TestCase
{
    private RawgAchievementService $service;

    public function setUp(): void
    {
        parent::setUp();

        Config::set('services.rawg.host', $this->faker->url());
        Config::set('services.rawg.api_key', $this->faker->password(8, 12));
    }

    public function test_should_return_achievements()
    {
        $this->service = resolve(RawgAchievementService::class, [
            'client' => $this->createClientMock()
        ]);

        $result = $this->service->getGameAchievements($this->faker->name());

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isNotEmpty());

        $item = $result->first();

        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('name', $item);
        $this->assertArrayHasKey('description', $item);
        $this->assertArrayHasKey('image', $item);
        $this->assertArrayHasKey('percent', $item);
    }

    private function createClientMock()
    {
        $contents = file_get_contents(
            storage_path('tests/rawg_achievements.json')
        );

        $body = Mockery::mock(Stream::class)->makePartial();
        $body->shouldReceive('getContents')->andReturn($contents);

        $response = Mockery::mock(Response::class)->makePartial();
        $response->shouldReceive('getBody')->andReturn($body);

        $client = Mockery::mock(Client::class)->makePartial();
        $client->shouldReceive('request')->andReturn($response);

        return $client;
    }
}
