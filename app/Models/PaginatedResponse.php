<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PaginatedResponse
{
    public function __construct(
        private Collection $data,
        private int $perPage,
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
            'per_page'      => $this->perPage,
            'current_page'  => $this->page,
            'last_page'     => ceil($this->total / $this->perPage),
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
