<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class FilteredCatalogPaginationDataProvider
 * Data provider to get pagination structure with filter in catalog page.
 *
 * @package App\Services\DataProviders\ClientProductList\Pagination
 */
class FilteredCatalogPaginationDataProvider implements PaginationDataProviderInterface
{
    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

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
     * @param array $filterData
     * @param $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        array $filterData,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->filterData = $filterData;
        $this->sort = $sort;
    }

    public function getPaginationStructure($elementsOnPage, $page)
    {
        return $this->catalogProductRepository->allPublishedInTreeByPage(
            null,
            $page,
            $elementsOnPage,
            $this->sort,
            $this->filterData
        );
    }
}
