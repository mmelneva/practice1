<?php namespace App\Services\FormProcessors;

use App\Models\ProductTypePage;
use App\Services\Cache\ProductTypePageSortCache;
use App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface;
use App\Services\Validation\ProductTypePage\ProductTypePageLaravelValidator;
use App\Services\Validation\ValidableInterface;

class ProductTypePageFormProcessor
{
    /**
     * @var ProdTypePageAssociationRepoInterface
     */
    private $prodTypePageAssociationRepo;

    /**
     * @var ValidableInterface
     */
    private $validator;
    private $productTypePageSortCache;

    /**
     * ProductTypePageFormProcessor constructor.
     * @param ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo
     * @param ProductTypePageLaravelValidator $validator
     * @param ProductTypePageSortCache $productTypePageSortCache
     */
    public function __construct(
        ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo,
        ProductTypePageLaravelValidator $validator,
        ProductTypePageSortCache $productTypePageSortCache
    ) {
        $this->prodTypePageAssociationRepo = $prodTypePageAssociationRepo;
        $this->validator = $validator;
        $this->productTypePageSortCache = $productTypePageSortCache;
    }


    public function save(ProductTypePage $productTypePage, array $data = [])
    {
        $this->validator->setCurrentId($productTypePage->id);
        if ($this->validator->with($data)->passes()) {
            $productTypePage->fill($data);
            $productTypePage->save();

            // Update product associations
            $productListWay = array_get($data, 'product_list_way');
            if ($productListWay == ProductTypePage::WAY_MANUAL) {
                $this->updateManualProducts($productTypePage, array_get($data, 'associated_products.manual'));
            } elseif ($productListWay == ProductTypePage::WAY_FILTERED) {
                $this->updateFilteredProducts($productTypePage, array_get($data, 'associated_products.filtered'));
            }
            $this->updateRandomSortScheme($productTypePage);

            return $productTypePage;
        } else {
            return null;
        }
    }


    /**
     * Update product association data for manual checked products.
     *
     * @param ProductTypePage $productTypePage
     * @param $associatedProductsData
     */
    private function updateManualProducts(ProductTypePage $productTypePage, $associatedProductsData)
    {
        if (!is_array($associatedProductsData)) {
            return;
        }

        $preparedProductIds = [];
        foreach ($associatedProductsData as $productId => $associationData) {
            if (
                !is_array($associationData) ||
                array_get($associationData, 'manual') == 0 && count($associationData) == 1
            ) {
                continue;
            }

            $this->prodTypePageAssociationRepo->updateAssociation(
                $productTypePage->id,
                $productId,
                $associationData
            );

            $preparedProductIds[] = $productId;
        }

        $this->prodTypePageAssociationRepo->uncheckManualExceptProducts($productTypePage->id, $preparedProductIds);
    }


    /**
     * Update product association data for filtered products.
     *
     * @param ProductTypePage $productTypePage
     * @param $associatedProductsData
     */
    private function updateFilteredProducts(ProductTypePage $productTypePage, $associatedProductsData)
    {
        if (!is_array($associatedProductsData)) {
            return;
        }

        foreach ($associatedProductsData as $productId => $associationData) {
            $this->prodTypePageAssociationRepo->updateAssociation(
                $productTypePage->id,
                $productId,
                $associationData
            );
        }
    }

    private function updateRandomSortScheme(ProductTypePage $productTypePage)
    {
        if ($productTypePage->use_sort_scheme) {
            if (empty($productTypePage->sort_scheme)) {
                $this->productTypePageSortCache->rebuildForProductTypePage($productTypePage);
            }
        } else {
            $productTypePage->sort_scheme = null;
            $productTypePage->save();
        }

    }

    /**
     * Get errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }
}
