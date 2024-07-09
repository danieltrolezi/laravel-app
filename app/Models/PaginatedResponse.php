<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class PaginatedResponse
{
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
        private int $page,
        private int $total
    ) {
    }

    /**
     * @return array
     */
    public function getContents(): array
    {
        return [
            'total'         => $this->total,
            'page_size'     => $this->pageSize,
            'current_page'  => $this->page,
            'last_page'     => ceil($this->total / $this->pageSize),
            'next_pageint_url' => $this->getPageUrl(-1),
            'data'          => $this->data
        ];
    }

    /**
     * @param integer $pageIncrement
     * @return string
     */
    private function getPageUrl(int $pageIncrement): string
    {
        if ($this->page === 1 && $pageIncrement < 0) {
            return '';
        }

        $request = resolve(Request::class);
        $query = $request->query();
        $url = url()->current();

        $query['page'] = $this->page + $pageIncrement;

        return $url . '?' . http_build_query($query);
    }
}
