<?php namespace App\Services\Cache;

use App\Models\ProductTypePage;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterUrl;
use App\Services\Catalog\FilterUrlParser\FilterUrlParser;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;
use App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface;

class ProductTypePageSortCache
{
    private $productTypePageRepository;

    public function __construct(
        ProductTypePageRepositoryInterface $productTypePageRepository,
        FilterUrlParser $filterUrlParser,
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo
    ) {
        $this->productTypePageRepository = $productTypePageRepository;
        $this->filterUrlParser = $filterUrlParser;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->prodTypePageAssociationRepo = $prodTypePageAssociationRepo;
    }

    public function rebuildAll()
    {
        \DB::disableQueryLog();

        $productTypePages = $this->productTypePageRepository->all();
        foreach ($productTypePages as $productTypePage) {
            $this->rebuildForProductTypePage($productTypePage);
        }
        \DB::enableQueryLog();
    }

    public function rebuildForProductTypePage(ProductTypePage $productTypePage)
    {
        $products = [];
        switch ($productTypePage->product_list_way) {
            case ProductTypePage::WAY_FILTERED:
                $products = $this->getFilteredProducts($productTypePage);
                break;
            case ProductTypePage::WAY_MANUAL:
                $products = $this->getManualProducts($productTypePage);
                break;
        }

        if ($products) {
            $ids = $products;
            shuffle($ids);
            $ids = implode(',', $ids);

            if ($ids) {
                $productTypePage->sort_scheme = $ids;
                $productTypePage->save();
            }
        }
    }

    private function getFilteredProducts(ProductTypePage $productTypePage)
    {
        try {
            //list($category, $filterData, $sort) = $this->filterUrlParser->getFilterDataFromUrl($productTypePage->filter_query);
            list($filterData, $sort) = $this->filterUrlParser->getFilterDataWithoutCategoryFromUrl($productTypePage->filter_query);

            $filteredProducts = $this->catalogProductRepository->allFilteredForProductType(
                //$category->id,
                null,
                $productTypePage,
                $sort,
                $filterData
            );
            $filteredProducts = $filteredProducts->lists('id');

        } catch (IncorrectFilterUrl $e) {
            $filteredProducts = [];
        }

        return $filteredProducts;
    }

    private function getManualProducts(ProductTypePage $productTypePage)
    {
        $manualProducts = $this->prodTypePageAssociationRepo->getAssociationsForPage($productTypePage->id);
        $productsIds = [];
        foreach ($manualProducts as $assoc) {
            if ($assoc->manual) {
                $productsIds[] = $assoc->catalog_product_id;
            }
        }

        return $productsIds;
    }
}
