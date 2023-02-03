<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

use App\Models\ProductTypePage;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class ManualPaginationDataProvider
 * Data provider to get pagination structure for product type page products.
 *
 * @package App\Services\DataProviders\ClientProductList\Pagination
 */
class ManualPaginationDataProvider implements PaginationDataProviderInterface
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
     * @var string
     */
    private $sort;

    /**
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param ProductTypePage $productTypePage - product type page to get products.
     * @param $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProductTypePage $productTypePage,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->productTypePage = $productTypePage;
        $this->sort = $sort;
    }

    public function getPaginationStructure($elementsOnPage, $page)
    {
        return $this->catalogProductRepository->allPublishedInTreeForManualTypeByPage(
            $this->productTypePage,
            $page,
            $elementsOnPage,
            $this->sort
        );
    }
}
