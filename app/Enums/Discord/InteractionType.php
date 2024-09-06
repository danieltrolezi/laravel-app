<?php

namespace App\Enums\Discord;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'int')]
enum InteractionType: int
{
    use BaseEnum;

    case Ping = 1;
    case Command = 2;
    case MessageComponent = 3;
    case AutoComplete = 4;
}
