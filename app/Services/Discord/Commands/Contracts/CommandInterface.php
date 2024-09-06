<?php

namespace App\Services\Discord\Commands\Contracts;

interface CommandInterface
{
    public function exec(array $payload): array;
}
