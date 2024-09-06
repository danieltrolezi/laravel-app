<?php

namespace App\Enums\Discord;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'int')]
enum OptionType: int
{
    use BaseEnum;

    case String = 3;
}
