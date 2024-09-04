<?php

namespace Tests\Unit\Services\Rawg;

use App\Enums\Period;
use App\Enums\Rawg\RawgField;
use App\Enums\Rawg\RawgGenre;
use App\Models\Game;
use App\Models\PaginatedResponse;
use App\Services\Rawg\RawgFilterService;
use App\Services\Rawg\RawgGamesService;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class RawgGamesServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::set('services.rawg.host', $this->faker->url());
        Config::set('services.rawg.api_key', $this->faker->password(8, 12));
    }

    private function createFilterServiceSpy(array $defaultFilters, array $filters)
    {
        $expectedFilters = array_merge($defaultFilters, $filters);

        $mock = Mockery::spy(RawgFilterService::class);
        $mock->shouldReceive('getQueryFilters')
             ->once()
             ->with($filters, $defaultFilters)
             ->andReturn($expectedFilters);


        return $mock;
    }

    public function test_should_get_recommendations()
    {
        $genre = RawgGenre::Action->value;

        $filters = [
            RawgField::Page->value     => 2,
            RawgField::PageSize->value => 10
        ];

        $filterServiceSpy = $this->createFilterServiceSpy([
            RawgField::Dates->value    => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
            RawgField::Genres->value   => $genre,
            RawgField::Ordering->value => 'updated',
            RawgField::PageSize->value => 5,
            RawgField::Page->value     => 1
        ], $filters);

        $service = resolve(RawgGamesService::class, [
            'filterService' => $filterServiceSpy,
            'client'        => $this->createClientMock('rawg_games.json')
        ]);

        $result = $service->getRecommendations($genre, $filters);

        $this->assertInstanceOf(PaginatedResponse::class, $result);
        $this->assertInstanceOf(Game::class, $result->getContents()['data']->first());
    }

    public function test_should_get_upcoming_releases()
    {
        $period = Period::Week->value;
        $parsedPeriod = strtolower(Period::from($period)->name);

        $filters = [
            RawgField::Page->value     => 2,
            RawgField::PageSize->value => 10
        ];

        $filterServiceSpy = $this->createFilterServiceSpy([
            RawgField::Dates->value    => date('Y-m-d') . ',' . date('Y-m-d', strtotime('+1 ' . $parsedPeriod)),
            RawgField::Ordering->value => 'released',
            RawgField::PageSize->value => 25,
            RawgField::Page->value     => 1
        ], $filters);

        $service = resolve(RawgGamesService::class, [
            'filterService' => $filterServiceSpy,
            'client'        => $this->createClientMock('rawg_games.json')
        ]);

        $result = $service->getUpcomingReleases($period, $filters);

        $this->assertInstanceOf(PaginatedResponse::class, $result);
        $this->assertInstanceOf(Game::class, $result->getContents()['data']->first());
    }
}
