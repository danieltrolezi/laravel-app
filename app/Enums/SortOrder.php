<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum SortOrder: string
{
    use BaseEnum;

    case ASC = 'ASC';
    case DESC = 'DESC';
}
