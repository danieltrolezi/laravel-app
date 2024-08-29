<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserSettingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_should_return_is_root()
    {
        $this->createUser();
        $settings = UserSetting::first();

        $this->assertInstanceOf(User::class, $settings->user);
    }
}
