<?php namespace App\Services\DataProviders\ClientProductList\Filter;

use App\Models\CatalogCategory;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class DefaultFilterDataProvider
 * Default filter, which receives filter data.
 *
 * @package App\Services\DataProviders\ClientProductList\Filter
 */
class DefaultFilterDataProvider implements FilterDataProviderInterface
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
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param CatalogCategory $category - category to filter in.
     * @param array $filterData - filter data.
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogCategory $category,
        array $filterData = []
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->category = $category;
        $this->filterData = $filterData;
    }

    public function getFilterVariants()
    {
        return $this->catalogProductRepository->filterVariants($this->category->id, $this->filterData);
    }

    public function getCleanFilterRequestData()
    {
        return $this->catalogProductRepository->clearFilterVariants($this->category->id, $this->filterData);
    }
}
