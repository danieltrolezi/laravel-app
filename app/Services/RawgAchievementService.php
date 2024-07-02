<?php

namespace App\Services;

use App\Enums\RawgField;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RawgAchievementService extends RawgBaseService
{
    /**
     * @param string $game
     * @param array $order
     * @return Collection
     */
    public function getGameAchievements(
        string $game,
        array $order
    ): Collection {
        $response = $this->call(uri: 'games/' . $game . '/achievements');

        $response = $this->orderAchievements(
            $response['results'],
            Arr::get($order, 'order_by', 'id'),
            Arr::get($order, 'sort_order', 'ASC')
        );

        return collect($response);
    }

    /**
     * @param array $data
     * @param string $orderBy
     * @param string $sortOrder
     * @return array
     */
    private function orderAchievements(
        array $data,
        string $orderBy = 'id',
        string $sortOrder = 'ASC'
    ): array {
        usort($data, function ($a, $b) use ($orderBy, $sortOrder) {
            if ($sortOrder === 'ASC') {
                if ($a[$orderBy] === $b[$orderBy]) {
                    return $a[RawgField::Slug->value] > $b[RawgField::Slug->value];
                }

                return $a[$orderBy] > $b[$orderBy];
            }

            if ($a[$orderBy] === $b[$orderBy]) {
                return $a[RawgField::Slug->value] < $b[RawgField::Slug->value];
            }

            return $a[$orderBy] < $b[$orderBy];
        });

        return $data;
    }
}
