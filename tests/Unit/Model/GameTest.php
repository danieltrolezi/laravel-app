<?php

namespace Test\Unit\Models;

use App\Models\Game;
use Database\Factories\GameFactory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GameTest extends TestCase
{
    public function test_should_create_instance()
    {
        $game = Game::factory()->make();
        $this->assertInstanceOf(Game::class, $game);
    }

    #[DataProvider('provider_required_fields')]
    public function test_should_validate_required_fields(string $field)
    {
        $payload = (new GameFactory())->definition();
        Arr::forget($payload, $field);

        $this->expectException(ValidationException::class);

        new Game($payload);
    }

    public static function provider_required_fields(): array
    {
        return [
            ['id'],
            ['name'],
            ['slug'],
            ['platforms.0.id'],
            ['platforms.0.name'],
            ['platforms.0.slug'],
        ];
    }
}
