<?php

namespace App\Enums\Rawg;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum RawgField: string
{
    use BaseEnum;

    case Id = 'id';
    case Name = 'name';
    case Slug = 'slug';
    case BgImage = 'background_image';
    case Released = 'released';
    case Dates = 'dates';
    case Genres = 'genres';
    case Platforms = 'platforms';
    case Stores = 'stores';
    case Ordering = 'ordering';
    case PageSize = 'page_size';
    case Page = 'page';
}
