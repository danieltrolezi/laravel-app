<?php

namespace App\Enums\Discord;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'int')]
enum ButtonStyle: int
{
    use BaseEnum;

    case Primary = 1;
    case Secundary = 2;
}
