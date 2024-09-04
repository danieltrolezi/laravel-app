<?php

namespace App\Services\Discord\Commands;

interface CommandInterface
{
    public function exec(array $payload): array;
}
