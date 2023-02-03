<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

use App\Models\ProductTypePage;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

class FilteredProductTypePaginationDataProvider implements PaginationDataProviderInterface
{
    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /**
     * @var ProductTypePage
     */
    private $productTypePage;
    /**
     * @var array
     */
    private $filterData;
    /**
     * @var string
     */
    private $sort;

    /**
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param ProductTypePage $productTypePage
     * @param array $filterData
     * @param $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProductTypePage $productTypePage,
        array $filterData,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->productTypePage = $productTypePage;
        $this->filterData = $filterData;
        $this->sort = $sort;
    }

    public function getPaginationStructure($elementsOnPage, $page)
    {
        return $this->catalogProductRepository->allPublishedInTreeForTypeByPage(
            null,
            $this->productTypePage,
            $page,
            $elementsOnPage,
            $this->sort,
            $this->filterData
        );
    }
}
