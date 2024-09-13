<?php

namespace Tests\Unit\Services\Rawg;

use App\Enums\SortOrder;
use App\Services\Rawg\RawgAchievementService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RawgAchievementServiceTest extends TestCase
{
    private RawgAchievementService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->prepRawgForUnitTesting();
        $this->service = resolve(RawgAchievementService::class, [
            'client' => $this->createClientMock('rawg_achievements.json')
        ]);
    }

    public function test_should_return_achievements()
    {
        $result = $this->service->getGameAchievements($this->faker->name());

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isNotEmpty());

        $item = $result->first();

        $this->assertEquals(126805, $item['id']);
        $this->assertEquals('Master Marksman', $item['name']);
        $this->assertEquals('Kill 50 human and nonhuman opponents by striking ' .
                            'them in the head with a crossbow bolt.', $item['description']);
        $this->assertEquals('https://media.rawg.io/media/achievements/' .
                            '233/23314da942731f8ce34378917a703aaf.jpg', $item['image']);
        $this->assertEquals('9.14', $item['percent']);
    }

    public function test_should_order_achievements()
    {
        $result = $this->service->getGameAchievements(
            $this->faker->name(),
            [
                'order_by'   => 'percent',
                'sort_order' => SortOrder::DESC->value
            ]
        );

        $result = $result->pluck('id')->toArray();

        $this->assertEquals([
            152157,
            152158,
            140771,
            148021,
            140770,
            135938,
            126805,
            177407,
            167134,
        ], $result);
    }
}
