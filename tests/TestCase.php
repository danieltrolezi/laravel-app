<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

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

        return User::factory()->hasSettings(1)->create($data)->load('settings');
    }

    protected function generateGameCollection(int $total): Collection
    {
        $games = [];

        for ($i = 0; $i < $total; $i++) {
            $games[] = Game::factory()->make();
        }

        return collect($games);
    }
}
