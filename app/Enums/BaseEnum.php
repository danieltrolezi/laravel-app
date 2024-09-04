<?php

namespace App\Enums;

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
        return array_combine(self::names(), self::values());
    }

    /**
     * @return string
     */
    public static function namesAsString(string $separator = ','): string
    {
        return implode($separator, self::names());
    }

    /**
     * @return string
     */
    public static function valuesAsString(string $separator = ','): string
    {
        return implode($separator, self::values());
    }

    /**
     * @return array
     */
    public static function casesAsArray(): array
    {
        return array_map(fn($case) => (array) $case, self::cases());
    }
}
