<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $env = match (config('app.url')) {
            'http://laravel-app.local' => 'local',
            default => 'production'
        };

        Health::checks([
            EnvironmentCheck::new()->expectEnvironment($env),
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            RedisCheck::new()
        ]);
    }
}
