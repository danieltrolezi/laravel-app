<?php

namespace Tests\Unit\Models;

use App\Models\PaginatedResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaginatedResponseTest extends TestCase
{
    #[DataProvider('provider_contents_settings')]
    public function test_should_get_contents(
        int $total,
        int $pageSize,
        int $currentPage
    ) {
        $lastPage = ceil($total / $pageSize);
        $games = $this->generateGameCollection($total);

        $url = url()->current();
        $nextPageUrl = $prevPageUrl = '';

        if ($currentPage < $lastPage) {
            $nextPageUrl = $url . '?' . http_build_query(['page' => $currentPage + 1]);
        }

        if ($currentPage > 1) {
            $prevPageUrl = $url . '?' . http_build_query(['page' => $currentPage - 1]);
        }

        $paginatedResponse = new PaginatedResponse($games, $pageSize, $currentPage, $total);
        $contents = $paginatedResponse->getContents();

        $this->assertEquals($total, $contents['total']);
        $this->assertEquals($pageSize, $contents['page_size']);
        $this->assertEquals($currentPage, $contents['current_page']);
        $this->assertEquals($lastPage, $contents['last_page']);
        $this->assertEquals($nextPageUrl, $contents['next_page_url']);
        $this->assertEquals($prevPageUrl, $contents['prev_page_url']);
        $this->assertEquals($games, $contents['data']);
    }

    public static function provider_contents_settings(): array
    {
        return [
            [10, 10, 1],
            [22, 10, 1],
            [22, 10, 2],
            [22, 10, 3],
            [22, 10, 4]
        ];
    }
}
