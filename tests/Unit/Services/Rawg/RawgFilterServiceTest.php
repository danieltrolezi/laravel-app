<?php

namespace Tests\Unit\Services\Rawg;

use App\Enums\Platform;
use App\Enums\Rawg\RawgField;
use App\Enums\Rawg\RawgGenre;
use App\Enums\Rawg\RawgPlatform;
use App\Services\Rawg\RawgFilterService;
use Tests\TestCase;

class RawgFilterServiceTest extends TestCase
{
    public function test_should_validate_query_filters()
    {
        $service = resolve(RawgFilterService::class);

        $expected = [
            RawgField::Dates->value     => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
            RawgField::Genres->value    => RawgGenre::Action->value,
            RawgField::Ordering->value  => 'updated',
            RawgField::Platforms->value => RawgPlatform::PC->value,
            RawgField::Page->value      => 2,
        ];

        $result = $service->getQueryFilters(
            filters: [
                RawgField::Dates->value     => $this->faker->date() . ',' . $this->faker->date(),
                RawgField::Platforms->value => Platform::PC->value,
                RawgField::Page->value      => $expected[RawgField::Page->value],
            ],
            default: [
                RawgField::Dates->value    => $expected[RawgField::Dates->value],
                RawgField::Genres->value   => $expected[RawgField::Genres->value],
                RawgField::Ordering->value => $expected[RawgField::Ordering->value],
                RawgField::Page->value     => 1
            ]
        );

        $this->assertEquals($expected, $result);
    }
}
