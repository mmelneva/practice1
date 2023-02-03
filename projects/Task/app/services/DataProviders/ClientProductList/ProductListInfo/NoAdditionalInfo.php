<?php namespace App\Services\DataProviders\ClientProductList\ProductListInfo;

/**
 * Class NoAdditionalInfo
 * Data provider which returns no info for product list.
 *
 * @package App\Services\DataProviders\ClientProductList\ProductListInfo
 */
class NoAdditionalInfo implements ProductListInfoInterface
{
    /**
     * @inheritdoc
     */
    public function getAdditionalInfoForProductList($productList)
    {
        return [];
    }
}
