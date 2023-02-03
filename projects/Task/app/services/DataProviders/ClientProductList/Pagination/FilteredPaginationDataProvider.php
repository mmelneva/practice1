<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

use App\Models\CatalogCategory;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class FilteredPaginationDataProvider
 * Data provider to get pagination structure with filter.
 *
 * @package App\Services\DataProviders\ClientProductList\Pagination
 */
class FilteredPaginationDataProvider implements PaginationDataProviderInterface
{
    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;
    /**
     * @var CatalogCategory
     */
    private $category;
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
     * @param CatalogCategory $category
     * @param array $filterData
     * @param $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogCategory $category,
        array $filterData,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->category = $category;
        $this->filterData = $filterData;
        $this->sort = $sort;
    }

    public function getPaginationStructure($elementsOnPage, $page)
    {
        return $this->catalogProductRepository->allPublishedInTreeByPage(
            $this->category->id,
            $page,
            $elementsOnPage,
            $this->sort,
            $this->filterData
        );
    }
}
