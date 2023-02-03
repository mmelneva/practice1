<?php namespace App\Commands;

use App\Models\ProductTypePage;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterUrl;
use App\Services\Catalog\FilterUrlParser\FilterUrlParser;
use App\Services\DataProviders\ClientProductList\Pagination\EmptyPaginationDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\ManualPaginationDataProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;
use Illuminate\Console\Command;

class RecountProductTypePages extends Command {
    protected $name = 'app:recount-product-type-pages';
    protected $description = 'Update products count for each product type page.';

    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @var ProductTypePageRepositoryInterface
     */
    private $productTypePageRepository;

    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /**
     * @var FilterUrlParser
     */
    private $filterUrlParser;

    public function fire() {
        //to prevent memory leak
        \DB::disableQueryLog();

        $this->catalogCategoryRepository = \App::make(
                'App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface'
        );
        $this->catalogProductRepository = \App::make(
                'App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface'
        );
        $this->productTypePageRepository = \App::make(
                'App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface'
        );

        $this->filterUrlParser = new FilterUrlParser($this->catalogCategoryRepository, $this->catalogProductRepository);

        $this->updateProductTypeCount();

//        echo "finish \r\n";
    }

    public function updateProductTypeCount() {


        $productPages = $this->productTypePageRepository->all();
        foreach ($productPages as $key => $productTypePage) {
            if ($productTypePage->product_list_way == ProductTypePage::WAY_FILTERED) {

                $productsCountData = $this->getFilteredProductsCountData($productTypePage);

            } elseif ($productTypePage->product_list_way == ProductTypePage::WAY_MANUAL) {

                $productsCountData = $this->getManualProductsCountData($productTypePage);

            } else {
                $productsCountData = [];
            }

            if (count($productsCountData) > 0) {
                $productTypePage->timestamps = false;
                $productTypePage->products_count =  array_get($productsCountData, 'total', 0);;
                $productTypePage->products_count_published = array_get($productsCountData, 'published', 0);;
                $productTypePage->save();

            }
        }
    }

    /**
     * @param ProductTypePage $productTypePage
     * @return array|mixed
     */
    private function getFilteredProductsCountData(ProductTypePage $productTypePage) {
        try {
            $filterUrl = $productTypePage->filter_query;

            list($filterData, $sort) = $this->filterUrlParser->getFilterDataWithoutCategoryFromUrl($filterUrl);

        } catch (IncorrectFilterUrl $e) {
            list($filterData, $sort) = [null, null];
        }

        if (is_null($filterData)) {

            $productsCountData = $this->getDefaultProductCountData();
        } else {

            $filteredProducts = $this->catalogProductRepository->allFilteredForProductType(
                    null,
                    $productTypePage,
                    $sort,
                    $filterData,
                    false
            );
            $filteredProductsPublished = $this->catalogProductRepository->allFilteredForProductType(
                    null,
                    $productTypePage,
                    $sort,
                    $filterData,
                    true
            );

            $productsCountData = array(
                    'total' => $filteredProducts->count(),
                    'published' => $filteredProductsPublished->count()
            );

        }

        return $productsCountData;
    }

    /**
     * @param ProductTypePage $productTypePage
     * @return EmptyPaginationDataProvider|ManualPaginationDataProvider
     */
    private function getManualProductsCountData(ProductTypePage $productTypePage) {
        $category = $this->catalogCategoryRepository->getManualCategoryForProductTypePage($productTypePage);

        if (is_null($category)) {
            $productsCountData = $this->getDefaultProductCountData();
        } else {
            $productsCountData = $this->catalogProductRepository->getPublishedProductsCountDataForManualProductTypePage(
                    $productTypePage
            );
        }

        return $productsCountData;
    }

    private function getDefaultProductCountData() {
        return [
                'total' => 0,
                'published' => 0,
        ];
    }
}
