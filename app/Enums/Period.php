<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum Period: string
{
    use BaseEnum;

    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
}
