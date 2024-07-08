<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

enum Platform: string
{
    use BaseEnum;

    case PC = 'pc';
    case Playstation5 = 'playstation5';
    case XboxSeriesX = 'xbox-series-x';
    case Switch = 'switch';
}