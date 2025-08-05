<?php

namespace App\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationResponse
{

    public static function formatPagination(LengthAwarePaginator $paginator)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'total_pages' => $paginator->lastPage(),
            'total_items' => $paginator->total(),
            'per_page' => $paginator->perPage(),
        ];
    }
}
