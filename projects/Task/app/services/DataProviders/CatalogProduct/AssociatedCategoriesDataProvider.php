<?php namespace App\Services\DataProviders\CatalogProduct;

use App\Models\CatalogProduct;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;

/**
 * Class AssociatedCategoriesDataProvider
 * @package App\Services\DataProviders\CatalogProduct
 */
class AssociatedCategoriesDataProvider
{
    public function __construct(CatalogCategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAssociatedCategoriesFormData(CatalogProduct $product)
    {
        $associatedCategoriesIds = $this->categoryRepository->getIdListForAssociatedProduct($product);

        return [
            'attached_associated_categories' => $associatedCategoriesIds,
            'associated_categories_variants' => $this->categoryRepository->getCategoriesVariants(),
        ];
    }
}
