<?php

namespace App\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->setRequestLogging();
        $this->setCommandLogContext();
    }

    private function setRequestLogging(): void
    {
        if (!config('app.debug')) {
            return;
        }

        $this->app['events']->listen(RequestHandled::class, function (RequestHandled $event) {
            $responseContent = $event->response->getContent();
            $responseContent = strlen($responseContent) > 120
                ? substr($responseContent, 0, 120) . '...'
                : $event->response->getContent();

            Log::info('Request & Response', [
                'method'     => $event->request->method(),
                'url'        => $event->request->fullUrl(),
                'status'     => $event->response->getStatusCode(),
                'ip'         => $event->request->ip(),
                'client_ip'  => $event->request->getClientIp(),
                'user_id'    => $event->request->user()?->id,
                'user_agent' => $event->request->userAgent(),
                'referer'    => $event->request->header('Referer'),
                'response'   => $responseContent,
            ]);
        });
    }

    private function setCommandLogContext(): void
    {
        $this->app['events']->listen(CommandStarting::class, function (CommandStarting $event) {
            Log::shareContext([
                'app'     => Str::slug(config('app.name')),
                'env'     => config('app.env'),
                'command' => $event->command,
            ]);
        });
    }
}
