<?php namespace App\Services\DataProviders\ClientProductList\ProductListInfo;

/**
 * Interface ProductListInfoInterface
 * Data provider to get additional info for product list per product.
 *
 * @package App\Services\DataProviders\ClientProductList\ProductListInfo
 */
interface ProductListInfoInterface
{
    /**
     * Get array of additional info per product.
     *
     * @param $productList
     * @return array
     */
    public function getAdditionalInfoForProductList($productList);
}
