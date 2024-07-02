<?php

namespace App\Enums;

use App\Enums\Traits\BaseEnum;

enum RawgField: string
{
    use BaseEnum;

    case Slug = 'slug';
    case Dates = 'dates';
    case Genre = 'genre';
    case Platforms = 'platforms';
    case Ordering = 'ordering';
    case PageSize = 'page_size';
    case Page = 'page';
}
