<?php

namespace Tests\Unit\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CreateRootTest extends TestCase
{
    use DatabaseMigrations;

    public function test_should_create_root()
    {
        Artisan::call('app:create-root');

        $this->assertDatabaseHas('users', [
            'name'  => config('auth.root.name'),
            'email' => config('auth.root.email'),
        ]);
    }
}
