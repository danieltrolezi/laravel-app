<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

enum Period: string
{
    use BaseEnum;

    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
}
