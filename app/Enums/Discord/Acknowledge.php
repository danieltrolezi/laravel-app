<?php

namespace App\Enums\Discord;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'int')]
enum Acknowledge: int
{
    use BaseEnum;

    case Pong = 1;
    case ChannelMessageWithSource = 4;
    case AutoCompleteResult = 8;
}
