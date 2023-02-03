<?php namespace App\Services\DataProviders\ClientProductList\Sorting;

use App\Models\CatalogCategory;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class DefaultSortingDataProvider
 * Data provider to get data to build sorting variants for category.
 *
 * @package App\Services\DataProviders\ClientProductList\Sorting
 */
class DefaultSortingDataProvider implements SortingDataProviderInterface
{
    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;
    /**
     * @var string
     */
    private $sort;

    /**
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param string $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->sort = $sort;
    }

    /**
     * @inheritdoc
     */
    public function getSortData()
    {
        $sortData = [];
        foreach ($this->catalogProductRepository->sortingVariants() as $sortName => $sortValue) {
            $sortData[] = [
                'name' => $sortName,
                'value' => $sortValue,
                'active' => $sortValue === $this->sort,
            ];
        }

        return $sortData;
    }

    /**
     * @inheritdoc
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @inheritdoc
     */
    public function prepareSortVariants($sort)
    {
        $sortVariants = $this->getSortData();
        if (count($sortVariants) > 0) {
            $hasActive = false;
            $sortVariants = array_map(
                function ($v) use (&$hasActive, $sort) {
                    $v['active'] = $v['value'] == $sort;
                    if ($v['active']) {
                        $hasActive = true;
                    }

                    return $v;
                },
                $sortVariants
            );

            if (!$hasActive) {
                $sortVariants[0]['active'] = true;
            }
        }

        return $sortVariants;
    }
}
