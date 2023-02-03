<?php namespace App\Services\FormProcessors\CatalogProductsSubProcessor;

use App\Models\CatalogProduct;

/**
 * Class AssociatedCategoriesSubProcessor
 * @package App\Services\FormProcessors\CatalogProductsSubProcessor
 */
class AssociatedCategoriesSubProcessor implements CatalogProductSubProcessorInterface
{
    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    public function process(CatalogProduct $catalogProduct, $formData)
    {
        $idList = array_get($formData, 'associated_categories', []);
        $defaultCategoryId = array_get($formData, 'category_id');
        if(!empty($defaultCategoryId)) {
            if (!in_array($defaultCategoryId, $idList)) {
                $idList[] = $defaultCategoryId;
            }

            $changes = $catalogProduct->associatedCategories()->sync($idList);
            if (!empty($changes['attached']) || !empty($changes['detached']) || $changes['updated']) {
                $catalogProduct->touch();
            }
        }
    }
}
