<?php

namespace App\Enums;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum Period: string
{
    use BaseEnum;

    case Week = 'next-7-days';
    case Month = 'next-30-days';
    case Year = 'next-12-months';
}
