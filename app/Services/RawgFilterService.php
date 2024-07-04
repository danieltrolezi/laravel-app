<?php

namespace App\Services;

use App\Enums\Platform;
use App\Enums\RawgField;
use App\Enums\RawgPlatform;
use Illuminate\Support\Arr;

class RawgFilterService
{
    /**
     * @param array $filters
     * @param array $default
     * @return array
     */
    public function getQueryFilters(array $filters, array $default): array
    {
        $filters = Arr::only($filters, $this->getAvailableApiFilters());
        $filters = array_merge($default, $filters);

        $this->handlePlatformsFilter($filters);

        return $filters;
    }

    /**
     * @return array
     */
    private function getAvailableApiFilters(): array
    {
        return [
            RawgField::Genres->value,
            RawgField::Platforms->value,
            RawgField::Ordering->value,
            RawgField::PageSize->value,
            RawgField::Page->value
        ];
    }

    /**
     * @param array $filters
     * @return void
     */
    private function handlePlatformsFilter(array &$filters): void
    {
        $plaformsKey = RawgField::Platforms->value;

        if (!empty($filters[$plaformsKey])) {
            $filters[$plaformsKey] = $this->parsePlatformsToRawgValues(
                $filters[$plaformsKey]
            );
        } else {
            $filters[$plaformsKey] = RawgPlatform::valuesAsString();
        }
    }

    /**
     * @param string $slugList
     * @return string
     */
    private function parsePlatformsToRawgValues(string $slugList): string
    {
        $slugs = explode(',', $slugList);
        $parsed = [];

        foreach ($slugs as $slug) {
            $platform = Platform::from($slug);
            $parsed[] = $this->parsePlatform($platform)->value;
        }

        return implode(', ', $parsed);
    }

    /**
     * @param Platform $platform
     * @return RawgPlatform
     */
    private function parsePlatform(Platform $platform): RawgPlatform
    {
        return RawgPlatform::fromName($platform->name);
    }
}
