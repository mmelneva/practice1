<?php namespace App\Services\Catalog\Filter\Filter;

/**
 * Interface FilterInterface
 * @package App\Services\Catalog\Filter\Filter
 */
interface FilterInterface
{
    /**
     * Get variants for filter.
     *
     * @param $query
     * @param array $filterData
     * @return mixed
     */
    public function getVariants($query, array $filterData);

    /**
     * Modify query according to filter data.
     *
     * @param $query
     * @param array $filterData
     * @return mixed
     */
    public function modifyQuery($query, array $filterData);

    /**
     * Clear filter data and leave only applicable data.
     *
     * @param $query
     * @param array $filterData
     * @return mixed
     */
    public function clearFilterData($query, array $filterData);

    /**
     * Compare two sets of filter data.
     *
     * @param $query
     * @param array $baseFilterData - base filter data.
     * @param array $filterData - filter data which we check to be equal with base filter data.
     * @return boolean
     */
    public function compareFilterData($query, array $baseFilterData, array $filterData);
}
