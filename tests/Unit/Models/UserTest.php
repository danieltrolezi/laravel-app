<?php

namespace Tests\Unit\Models;

use App\Enums\Scope;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    #[DataProvider('provider_scopes')]
    public function test_should_return_is_root(array $scopes, bool $expected)
    {
        $user = User::factory()->create([
            'scopes' => $scopes
        ]);

        $this->assertEquals($expected, $user->isRoot());
    }

    public static function provider_scopes(): array
    {
        return [
            [Scope::values(), true],
            [[Scope::Default->value], false],
        ];
    }
}
