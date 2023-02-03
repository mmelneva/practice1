<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ProductTypePage;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterUrl;
use App\Services\Catalog\FilterUrlParser\FilterUrlParser;
use App\Services\DataProviders\ClientProductList\PageUrl\CatalogPageUrlDataProvider;
use App\Services\DataProviders\ClientProductList\PageUrl\ProductTypePageUrlDataProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;

/**
 * Class FilterProxyController
 * Proxy controller for filter. It decides which page should show this filter query - catalog or product type page.
 *
 * @package App\Controllers\Client
 */
class FilterProxyController extends BaseController
{
    private $catalogCategoryRepository;
    private $catalogProductRepository;
    private $productTypePageRepository;
    private $filterUrlParser;

    public function __construct(
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProductTypePageRepositoryInterface $productTypePageRepository,
        FilterUrlParser $filterUrlParser
    ) {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->productTypePageRepository = $productTypePageRepository;
        $this->filterUrlParser = $filterUrlParser;
    }

    public function redirectToFilterUrl()
    {
        $filterData = \Input::get('filter');
        $sort = \Input::get('sort');

        $pageDataProvider = $this->getSameProductTypeUrlProvider($filterData, $sort);
        if (is_null($pageDataProvider)) {
            $pageDataProvider = new CatalogPageUrlDataProvider(
                $this->catalogProductRepository,
                $filterData,
                $sort
            );
            $productTypePageList = $this->productTypePageRepository->allPublishedWithFilter();
            foreach ($productTypePageList as $productTypePage) {
                $filterString = $productTypePage->filter_query;

                try {
                    list($productTypeFilterData, $productTypeSort) =
                        $this->filterUrlParser->getFilterDataWithoutCategoryFromUrl($filterString);
                } catch (IncorrectFilterUrl $e) {
                    continue;
                }

                if ($this->catalogProductRepository->compareFilterData(
                        null,
                        $productTypeFilterData,
                        $filterData
                    )
                    && $productTypeSort == $sort
                ) {
                    $pageDataProvider = new ProductTypePageUrlDataProvider($productTypePage->node);
                    break;
                }
            }
        }

        $targetUrl = $pageDataProvider->getPageUrl(1);

        return \Redirect::to($targetUrl);
    }


    /**
     * Get producty type url data provider for page.
     * Need it to show the same page but with different sort.
     *
     *
     * @param $filterData
     * @param $sort
     * @return ProductTypePageUrlDataProvider|null
     */
    private function getSameProductTypeUrlProvider($filterData, $sort)
    {
        $productTypePageId = \Input::get('product_type');
        if (is_null($productTypePageId)) {
            return null;
        }

        $productTypePage = $this->productTypePageRepository->findPublished($productTypePageId);
        if (is_null($productTypePage)) {
            return null;
        }

        $urlProvider = null;

        switch ($productTypePage->product_list_way) {
            case ProductTypePage::WAY_FILTERED:
                try {
                    list($productTypeCategory, $productTypeFilterData, $productTypeSort) =
                        $this->filterUrlParser->getFilterDataFromUrl($productTypePage->filter_query);
                } catch (IncorrectFilterUrl $e) {
                    break;
                }

                $sameFilterData = $this->catalogProductRepository->compareFilterData(
                    $productTypeCategory->id,
                    $productTypeFilterData,
                    $filterData
                );
                if ($sameFilterData) {
                    $urlProvider = new ProductTypePageUrlDataProvider(
                        $productTypePage->node,
                        $productTypeSort == $sort ? null : $sort
                    );
                }
                break;
            case ProductTypePage::WAY_MANUAL:
                $cleanFilterData = $this->catalogProductRepository->clearFilterVariants(
                    $productTypePage->manual_product_list_category_id,
                    $filterData
                );
                if (empty($cleanFilterData)) {
                    $urlProvider = new ProductTypePageUrlDataProvider($productTypePage->node, $sort);
                }
                break;
            default:
                break;
        }

        return $urlProvider;
    }
}
