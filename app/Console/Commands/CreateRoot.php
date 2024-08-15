<?php

namespace App\Console\Commands;

use App\Enums\Permission;
use App\Models\User;
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
        $user = User::where('email', config('auth.root.email'))->first();

        if (!$user) {
            $user = User::create([
                'name'     => config('auth.root.name'),
                'email'    => config('auth.root.email'),
                'password' => bcrypt(config('auth.root.password')),
            ]);

            $token = $user->createToken('default', Permission::values())->plainTextToken;

            if (app()->environment('local')) {
                $this->info('Token: ' . $token);
            }
        }
    }
}
