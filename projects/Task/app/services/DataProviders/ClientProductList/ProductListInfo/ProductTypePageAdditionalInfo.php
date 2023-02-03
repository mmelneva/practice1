<?php namespace App\Services\DataProviders\ClientProductList\ProductListInfo;

use App\Models\ProductTypePage;
use App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface;

/**
 * Class ProductTypePageAdditionalInfo
 * Data provider to get additional info for product for product type page - it will get it from associations.
 *
 * @package App\Services\DataProviders\ClientProductList\ProductListInfo
 */
class ProductTypePageAdditionalInfo implements ProductListInfoInterface
{
    /**
     * @var ProdTypePageAssociationRepoInterface
     */
    private $prodTypePageAssociationRepo;

    /**
     * @var ProductTypePage
     */
    private $productTypePage;

    /**
     * @param ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo
     * @param ProductTypePage $productTypePage - product type page which associations contains additional info.
     */
    public function __construct(
        ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo,
        ProductTypePage $productTypePage
    ) {
        $this->prodTypePageAssociationRepo = $prodTypePageAssociationRepo;
        $this->productTypePage = $productTypePage;
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalInfoForProductList($productList)
    {
        $productIdList = [];
        foreach ($productList as $product) {
            $productIdList[] = $product->id;
        }

        $productAssociations = $this->prodTypePageAssociationRepo->getAssociationsForPageAndProducts(
            $this->productTypePage->id,
            $productIdList
        );


        $productListAdditionalInfo = [];
        foreach ($productAssociations as $prodAssoc) {
            $prodAdditionalInfo = [];
            if (!empty($prodAssoc->name)) {
                $prodAdditionalInfo['name'] = $prodAssoc->name;
            }
            if (!empty($prodAssoc->small_content)) {
                $prodAdditionalInfo['small_content'] = $prodAssoc->small_content;
            }

            if (count($prodAdditionalInfo) > 0) {
                $productListAdditionalInfo[$prodAssoc->catalog_product_id] = $prodAdditionalInfo;
            }
        }

        return $productListAdditionalInfo;
    }
}
