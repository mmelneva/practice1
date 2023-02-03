<?php namespace App\Services\DataProviders\ClientProductList\Sorting;

/**
 * Interface SortingDataProviderInterface
 * Data provider to get data to build sorting variants.
 *
 * @package App\Services\DataProviders\ClientProductList\Sorting
 */
interface SortingDataProviderInterface
{
    /**
     * Get data to build sorting variants
     *
     * @return mixed
     */
    public function getSortData();

    /**
     * Get current sort.
     *
     * @return string
     */
    public function getSort();

    /**
     * Prepare sort variants.
     *
     * @param $sort
     * @return array
     */
    public function prepareSortVariants($sort);
}
