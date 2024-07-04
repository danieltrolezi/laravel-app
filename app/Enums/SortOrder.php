<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

enum SortOrder: string
{
    use BaseEnum;

    case ASC = 'ASC';
    case DESC = 'DESC';
}
