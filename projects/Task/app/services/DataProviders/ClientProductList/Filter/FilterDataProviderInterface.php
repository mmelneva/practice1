<?php namespace App\Services\DataProviders\ClientProductList\Filter;

/**
 * Interface FilterDataProviderInterface
 * Data provider, which returns data to build filter.
 *
 * @package App\Services\DataProviders\ClientProductList\Filter
 */
interface FilterDataProviderInterface
{
    /**
     * Get data to build filter.
     *
     * @return mixed
     */
    public function getFilterVariants();

    /**
     * Get clear filter request data.
     *
     * @return mixed
     */
    public function getCleanFilterRequestData();
}
