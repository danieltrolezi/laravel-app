<?php

namespace App\Enums;

use Illuminate\Support\Str;

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
     * @return integer
     */
    public static function count(): int
    {
        return count(self::cases());
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
    public static function friendlyCases(): array
    {
        return array_map(function ($case) {
            $name = strtoupper($case->name) === $case->name
                ? $case->name
                : Str::headline($case->name);

            return [
                'name'  => $name,
                'value' => $case->value,
            ];
        }, self::cases());
    }
}
