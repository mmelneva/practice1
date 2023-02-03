<?php namespace App\Services\DataProviders\ClientProductList;

use App\Services\DataProviders\CatalogProduct\AttributesOutputDataProvider;
use App\Services\DataProviders\ClientProductList\Filter\FilterDataProviderInterface;
use App\Services\DataProviders\ClientProductList\PageUrl\PageUrlDataProviderInterface;
use App\Services\DataProviders\ClientProductList\Pagination\PaginationDataProviderInterface;
use App\Services\DataProviders\ClientProductList\ProductListInfo\ProductListInfoInterface;
use App\Services\DataProviders\ClientProductList\Sorting\SortingDataProviderInterface;
use App\Services\Pagination\PaginationFactory;

/**
 * Class CatalogProductListDataProvider
 * Provider to get data for product list.
 *
 * @package App\Services\DataProviders\ClientProductList
 */
class CatalogProductListDataProvider
{
    const ELEMENTS_ON_PAGE = 60;

    private $attributesOutputDataProvider;
    private $paginationDataProvider;
    private $productListInfoDataProvider;
    private $sortingDataProvider;
    private $filterDataProvider;
    private $pageUrlDataProvider;
    private $paginationFactory;

    public function __construct(
        AttributesOutputDataProvider $attributesOutputDataProvider,
        PaginationDataProviderInterface $paginationDataProvider,
        ProductListInfoInterface $productListInfoDataProvider,
        SortingDataProviderInterface $sortingDataProvider,
        FilterDataProviderInterface $filterDataProvider,
        PageUrlDataProviderInterface $pageUrlDataProvider,
        PaginationFactory $paginationFactory
    ) {
        $this->attributesOutputDataProvider = $attributesOutputDataProvider;
        $this->paginationDataProvider = $paginationDataProvider;
        $this->productListInfoDataProvider = $productListInfoDataProvider;
        $this->sortingDataProvider = $sortingDataProvider;
        $this->filterDataProvider = $filterDataProvider;
        $this->pageUrlDataProvider = $pageUrlDataProvider;
        $this->paginationFactory = $paginationFactory;
    }



    /**
     * Get product data to show product list and related stuff.
     *
     * @param $page
     *
     * @return array
     */
    public function getProductListData($page)
    {
        $paginationStructure = $this->paginationDataProvider->getPaginationStructure(self::ELEMENTS_ON_PAGE, $page);

        $products = $paginationStructure['items'];
        $productListAdditionalInfo = $this->productListInfoDataProvider->getAdditionalInfoForProductList($products);

        $sort = $this->sortingDataProvider->getSort();
        $sortData = $this->sortingDataProvider->prepareSortVariants($sort);

        $filterVariants = $this->filterDataProvider->getFilterVariants();
        $cleanFilterRequest = $this->filterDataProvider->getCleanFilterRequestData();

        $paginationData = $this->paginationFactory->getPagination(
            function ($page) {
                return $this->pageUrlDataProvider->getPageUrl($page);
            },
            $products,
            $paginationStructure['total'],
            $paginationStructure['page'],
            $paginationStructure['limit']
        );

        return [
            'paginationData' => $paginationData,
            'productListAdditionalInfo' => $productListAdditionalInfo,
            'sort' => $sort,
            'sortData' =>  $sortData,
            'filterVariants' => $filterVariants,
            'cleanFilterRequest' => $cleanFilterRequest,
        ];
    }
}
