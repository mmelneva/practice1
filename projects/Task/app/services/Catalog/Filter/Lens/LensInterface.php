<?php namespace App\Services\Catalog\Filter\Lens;

/**
 * Interface LensInterface
 * Lens to filter queries and get available variants.
 *
 * @package App\Services\Catalog\Filter\Lens
 */
interface LensInterface
{
    /**
     * Modify query according to lens data.
     *
     * @param $query
     * @param $lensData
     * @return void
     */
    public function modifyQuery($query, $lensData);

    /**
     * Get variants for base query.
     * Null means no variants and this lens won't be used in lens aggregator.
     *
     * @param $query
     * @param $lensData
     * @return mixed
     */
    public function getVariants($query, $lensData);

    /**
     * Clean the lens data.
     *
     * @param $query
     * @param $lensData
     * @return mixed
     */
    public function cleanLensData($query, $lensData);

    /**
     * Compare two sets of lens data.
     *
     * @param $lensDataAlpha
     * @param $lensDataOmega
     * @return boolean
     */
    public function compareLensData($lensDataAlpha, $lensDataOmega);
}
