<?php

namespace App\Enums\Discord;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'int')]
enum ComponentType: int
{
    use BaseEnum;

    case ActionRow = 1;
    case Button = 2;
    case StringSelect = 3;
}
