<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RawgDomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createRootUser();
    }

    private function getCommonJsonStructure(): array
    {
        return [
            '*' => [
                'id',
                'name',
                'slug',
                'games_count',
                'image_background'
            ]
        ];
    }

    public function test_should_return_genres()
    {
        $res = $this->actingAs($this->user)->get('/api/rawg/domain/genres');

        $res->assertStatus(200);
        $res->assertJsonStructure($this->getCommonJsonStructure());
    }

    public function test_should_return_tags()
    {
        $res = $this->actingAs($this->user)->get('/api/rawg/domain/tags');

        $res->assertStatus(200);
        $res->assertJsonStructure($this->getCommonJsonStructure());
    }

    public function test_should_return_platforms()
    {
        $res = $this->actingAs($this->user)->get('/api/rawg/domain/platforms');

        $res->assertStatus(200);
        $res->assertJsonStructure($this->getCommonJsonStructure());
    }
}
