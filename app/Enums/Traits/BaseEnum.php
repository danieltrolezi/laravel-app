<?php

namespace App\Enums\Traits;

trait BaseEnum
{
    /**
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array
     */
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
