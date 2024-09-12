<?php

namespace App\Providers;

use App\Guards\JwtGuard;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->setRateLimit();
    }

    private function setHealthCheck(): void
    {
        $env = match (config('app.url')) {
            'http://gamewatch.local' => 'local',
            default                  => 'prod'
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

    private function setRateLimit(): void
    {
        RateLimiter::for('api', function (Request $request) {
            if ($request->user()) {
                $user = $request->user();

                return $user->isRoot()
                    ? Limit::none()
                    : Limit::perMinute(config('app.rate_limit.user'))->by($user->id);
            }

            return Limit::perMinute(config('app.rate_limit.guest'))->by($request->ip());
        });

        RateLimiter::for('discord', function (Request $request) {
            $user = $request->get('user');

            if (!empty($user)) {
                return Limit::perMinute(config('app.rate_limit.user'))->by($user['id']);
            }

            return Limit::perMinute(config('app.rate_limit.discord'));
        });
    }
}
