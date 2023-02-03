<?php namespace App\Services\DataProviders\ClientProductList\Filter;

use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class CatalogFilterDataProvider
 * Default filter, which receives filter data.
 *
 * @package App\Services\DataProviders\ClientProductList\Filter
 */
class CatalogFilterDataProvider implements FilterDataProviderInterface
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
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param array $filterData - filter data.
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        array $filterData = []
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->filterData = $filterData;
    }

    public function getFilterVariants()
    {
        return $this->catalogProductRepository->filterVariants(null, $this->filterData);
    }

    public function getCleanFilterRequestData()
    {
        return $this->catalogProductRepository->clearFilterVariants(null, $this->filterData);
    }
}
