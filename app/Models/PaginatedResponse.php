<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class PaginatedResponse
{
    private int $lastPage;

    #[OA\Property(property: 'total', type: 'integer')]
    #[OA\Property(property: 'page_size', type: 'integer')]
    #[OA\Property(property: 'current_page', type: 'integer')]
    #[OA\Property(property: 'last_page', type: 'integer')]
    #[OA\Property(property: 'next_page_url', type: 'string')]
    #[OA\Property(property: 'prev_page_url', type: 'string')]
    #[OA\Property(property: 'data', type: 'array', items: new OA\Items(
        //ref: '#/components/schemas/Game'
        oneOf: [
            new OA\Schema(ref: '#/components/schemas/Game'),
        ]
    ))]
    public function __construct(
        private Collection $data,
        private int $pageSize,
        private int $curentPage,
        private int $total
    ) {
        $this->lastPage = ceil($this->total / $this->pageSize);
    }

    /**
     * @return array
     */
    public function getContents(): array
    {
        return [
            'total'         => $this->total,
            'page_size'     => $this->pageSize,
            'current_page'  => $this->curentPage,
            'last_page'     => $this->lastPage,
            'next_page_url' => $this->getPageUrl(1),
            'prev_page_url' => $this->getPageUrl(-1),
            'data'          => $this->data
        ];
    }

    /**
     * @param integer $pageIncrement
     * @return string
     */
    private function getPageUrl(int $pageIncrement): string
    {
        if (
            $this->isFirstPageAndPrevLink($pageIncrement)
            || $this->isLastPageAndNextLink($pageIncrement)
        ) {
            return '';
        }

        $query = request()->query();
        $url = url()->current();
        $query['page'] = $this->curentPage + $pageIncrement;

        return $url . '?' . http_build_query($query);
    }

    private function isFirstPageAndPrevLink(int $pageIncrement): bool
    {
        return $this->curentPage === 1 && $pageIncrement < 0;
    }

    private function isLastPageAndNextLink(int $pageIncrement): bool
    {
        return $this->curentPage >= $this->lastPage && $pageIncrement > 0;
    }
}
