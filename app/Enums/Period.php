<?php

namespace App\Enums;

use App\Enums\BaseEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(type: 'string')]
enum Period: string
{
    use BaseEnum;

    case Next_7_Days = 'next-7-days';
    case Next_30_Days = 'next-30-days';
    case Next_12_Months = 'next-12-months';

    /**
     * @param string $name
     * @return string
     */
    public static function getTimeUnit(string $value): string
    {
        return match ($value) {
            self::Next_7_Days->value    => 'week',
            self::Next_30_Days->value   => 'month',
            self::Next_12_Months->value => 'year',
        };
    }
}
