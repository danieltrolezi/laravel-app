<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use App\Services\Discord\Commands\ReleasesCommand;
use App\Services\Discord\DiscordAppService;
use Illuminate\Console\Command;

class DispatchNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch notifications for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        resolve(DiscordAppService::class)->dispatchNotifications();
    }
}
