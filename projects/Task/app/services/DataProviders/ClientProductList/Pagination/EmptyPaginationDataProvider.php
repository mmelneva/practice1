<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

/**
 * Class EmptyPaginationDataProvider
 * Empty pagination structure.
 *
 * @package App\Services\DataProviders\ClientProductList\Pagination
 */
class EmptyPaginationDataProvider implements PaginationDataProviderInterface
{
    public function getPaginationStructure($elementsOnPage, $page)
    {
        return [
            'items' => [],
            'total' => 0,
            'limit' => $elementsOnPage,
            'page' => $page,
        ];
    }
}
