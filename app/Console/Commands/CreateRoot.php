<?php

namespace App\Console\Commands;

use App\Enums\Scope;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Console\Command;

class CreateRoot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-root';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the Root User for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $created = resolve(UserRepository::class)->createRoot();

        if ($created && app()->environment('local')) {
            $jwt = resolve(AuthService::class)->generateJWT([
                'email'    => config('auth.root.email'),
                'password' => config('auth.root.password')
            ]);

            $this->info('JWT: ' . $jwt['token']);
        }
    }
}
