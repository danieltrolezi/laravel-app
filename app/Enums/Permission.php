<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum Permission: string
{
    use BaseEnum;

    case Create = 'create';
    case Read = 'read';
    case Update = 'update';
    case Delete = 'delete';
    case RawgRead = 'rawg:read';
}
