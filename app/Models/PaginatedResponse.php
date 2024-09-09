<?php

namespace App\Models;

use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class PaginatedResponse
{
    public readonly int $lastPage;
    public readonly string $nextPageUrl;
    public readonly string $prevPageUrl;

    #[OA\Property(property: 'total', type: 'integer')]
    #[OA\Property(property: 'pageSize', type: 'integer')]
    #[OA\Property(property: 'currentPage', type: 'integer')]
    #[OA\Property(property: 'lastPage', type: 'integer')]
    #[OA\Property(property: 'nextPageUrl', type: 'string')]
    #[OA\Property(property: 'prevPageUrl', type: 'string')]
    #[OA\Property(property: 'data', type: 'array', items: new OA\Items(
        //ref: '#/components/schemas/Game'
        oneOf: [
            new OA\Schema(ref: '#/components/schemas/Game'),
        ]
    ))]
    public function __construct(
        public readonly Collection $data,
        public readonly int $pageSize,
        public readonly int $currentPage,
        public readonly int $total
    ) {
        $this->lastPage = ceil($this->total / $this->pageSize);
        $this->nextPageUrl = $this->getPageUrl(1);
        $this->prevPageUrl = $this->getPageUrl(-1);
    }

    /**
     * @return array
     */
    public function getContents(): array
    {
        return [
            'total'         => $this->total,
            'page_size'     => $this->pageSize,
            'current_page'  => $this->currentPage,
            'last_page'     => $this->lastPage,
            'next_page_url' => $this->nextPageUrl,
            'prev_page_url' => $this->prevPageUrl,
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
        $query['page'] = $this->currentPage + $pageIncrement;

        return $url . '?' . http_build_query($query);
    }

    /**
     * @param integer $pageIncrement
     * @return boolean
     */
    private function isFirstPageAndPrevLink(int $pageIncrement): bool
    {
        return $this->currentPage === 1 && $pageIncrement < 0;
    }

    /**
     * @param integer $pageIncrement
     * @return boolean
     */
    private function isLastPageAndNextLink(int $pageIncrement): bool
    {
        return $this->currentPage >= $this->lastPage && $pageIncrement > 0;
    }
}
