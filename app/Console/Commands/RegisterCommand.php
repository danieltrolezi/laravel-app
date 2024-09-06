<?php

namespace App\Console\Commands;

use App\Services\Discord\DiscordAppService;
use Illuminate\Console\Command;

class RegisterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:register-command {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Discord Command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(
            resolve(DiscordAppService::class)->registerCommand(
                $this->argument('name')
            )
        );
    }
}
