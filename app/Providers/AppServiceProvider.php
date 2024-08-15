<?php

namespace App\Providers;

use App\Guards\JwtGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
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
        $this->setHealthCheck();
        $this->setJwtGuard();
    }

    private function setHealthCheck(): void
    {
        $env = match (config('app.url')) {
            'http://laravel-app.local' => 'local',
            'http://laravel-app.qa'    => 'qa',
            default                    => 'production'
        };

        Health::checks([
            EnvironmentCheck::new()->expectEnvironment($env),
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            RedisCheck::new()
        ]);
    }

    private function setJwtGuard(): void
    {
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JwtGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
