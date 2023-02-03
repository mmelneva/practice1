<?php namespace App\Services\DataProviders\ClientProductList\PageUrl;

use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class CatalogPageUrlDataProvider
 * Data provider to get page url for pagination for catalog page.
 *
 * @package App\Services\DataProviders\ClientProductList\PageUrl
 */
class CatalogPageUrlDataProvider extends PageUrlDataProvider
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
     * @param $filterData - filter data.
     * @param string $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        $filterData,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->filterData = $filterData;
        $this->sort = $sort;
    }

    public function getPageUrl($page)
    {
        $pageUrl = $this->getPageUrlByBaseUrl(route('full_catalog'), $page);
        $cleanFilterData = $this->catalogProductRepository->clearFilterVariants(null, $this->filterData);

        $pageQuery = http_build_query(['filter' => $cleanFilterData, 'sort' => $this->sort]);
        if (!empty($pageQuery)) {
            $pageUrl .= '?' . $pageQuery;
        }

        return $pageUrl;
    }
}
