<?php

namespace App\Enums;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum Frequency: string
{
    use BaseEnum;

    case None = 'none';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
