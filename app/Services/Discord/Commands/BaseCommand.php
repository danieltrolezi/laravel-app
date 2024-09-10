<?php

namespace App\Services\Discord\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class BaseCommand
{
    protected ?User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        $className = get_called_class();
        $className = explode('\\', $className);
        $className = strtolower(array_pop($className));

        return str_replace('command', '', $className);
    }
}
