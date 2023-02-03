<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

/**
 * Interface PaginationDataProviderInterface
 * Data provider to get pagination results.
 *
 * @package App\Services\DataProviders\ClientProductList\Pagination
 */
interface PaginationDataProviderInterface
{
    /**
     * Get pagination data structure.
     *
     * @param $elementsOnPage
     * @param $page
     * @return array
     */
    public function getPaginationStructure($elementsOnPage, $page);
}
