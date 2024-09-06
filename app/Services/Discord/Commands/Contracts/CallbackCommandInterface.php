<?php

namespace App\Services\Discord\Commands\Contracts;

interface CallbackCommandInterface extends CommandInterface
{
    public function getName(): string;
    public function callback(array $payload): array;
}
