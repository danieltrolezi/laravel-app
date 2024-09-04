<?php

namespace Database\Factories;

use App\Enums\Platform;
use App\Enums\Rawg\RawgGenre;
use App\Enums\Rawg\RawgPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'              => fake()->randomNumber(1),
            'name'            => fake()->name(),
            'slug'            => fake()->slug(),
            'backgroundImage' => fake()->imageUrl(),
            'released'        => fake()->date(),
            'platforms'       => [
                [
                    'id'   => RawgPlatform::PC->value,
                    'name' => RawgPlatform::PC->name,
                    'slug' => Platform::PC->value
                ]
            ],
            'stores'          => [
                [
                    'id'   => fake()->randomNumber(1),
                    'name' => fake()->name(),
                    'slug' => fake()->slug()
                ]
            ],
            'genres'          => [
                [
                    'id'   => fake()->randomNumber(1),
                    'name' => RawgGenre::Action->name,
                    'slug' => RawgGenre::Action->value
                ]
            ],
        ];
    }
}
