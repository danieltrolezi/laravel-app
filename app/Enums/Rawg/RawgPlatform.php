<?php

namespace App\Enums\Rawg;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'integer')]
enum RawgPlatform: int
{
    use BaseEnum;

    case PC = 4;
    case Playstation5 = 187;
    case XboxSeriesX = 186;
    case Switch = 7;
}
