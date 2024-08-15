<?php

namespace App\Console\Commands;

use App\Enums\Permission;
use App\Models\User;
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
        $user = User::where('email', config('auth.root.email'))->first();

        if (!$user) {
            $user = new User();
            $user->name = config('auth.root.name');
            $user->email = config('auth.root.email');
            $user->password = bcrypt(config('auth.root.password'));
            $user->scopes = json_encode(Permission::values());
            $user->save();

            if (app()->environment('local')) {
                $token = resolve(AuthService::class)->generateJWT([
                    'email'    => $user->email,
                    'password' => $user->password
                ]);

                $this->info('JWT: ' . $token);
            }
        }
    }
}
