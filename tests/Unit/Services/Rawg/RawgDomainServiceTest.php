<?php

namespace Tests\Unit\Services\Rawg;

use App\Services\Rawg\RawgDomainService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RawgDomainServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->prepRawgForUnitTesting();
    }

    public function test_should_return_genres(): void
    {
        $service = resolve(RawgDomainService::class, [
            'client' => $this->createClientMock('rawg_domain_genres.json')
        ]);

        $result = $service->getGenres();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(4, $result[0]['id']);
        $this->assertEquals('Action', $result[0]['name']);
        $this->assertEquals('action', $result[0]['slug']);
    }

    public function test_should_return_tags()
    {
        $service = resolve(RawgDomainService::class, [
            'client' => $this->createClientMock('rawg_domain_tags.json')
        ]);

        $result = $service->getTags();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(31, $result[0]['id']);
        $this->assertEquals('Singleplayer', $result[0]['name']);
        $this->assertEquals('singleplayer', $result[0]['slug']);
    }

    public function test_should_return_platforms()
    {
        $service = resolve(RawgDomainService::class, [
            'client' => $this->createClientMock('rawg_domain_platforms.json')
        ]);

        $result = $service->getPlatforms();

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(4, $result[0]['id']);
        $this->assertEquals('PC', $result[0]['name']);
        $this->assertEquals('pc', $result[0]['slug']);
    }
}
