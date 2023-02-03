<?php namespace App\Services\DataProviders\ClientProductList\PageUrl;

use App\Models\CatalogCategory;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class CategoryPageUrlDataProvider
 * Data provider to get page url for pagination for category.
 *
 * @package App\Services\DataProviders\ClientProductList\PageUrl
 */
class CategoryPageUrlDataProvider extends PageUrlDataProvider
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
     * @param CatalogCategory $category - category.
     * @param $filterData - filter data.
     * @param string $sort
     */
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogCategory $category,
        $filterData,
        $sort
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->category = $category;
        $this->filterData = $filterData;
        $this->sort = $sort;
    }

    public function getPageUrl($page)
    {
        $pageUrl = $this->getPageUrlByBaseUrl(\UrlBuilder::getUrl($this->category), $page);
        $cleanFilterData = $this->catalogProductRepository->clearFilterVariants($this->category->id, $this->filterData);

        $pageQuery = http_build_query(['filter' => $cleanFilterData, 'sort' => $this->sort]);
        if (!empty($pageQuery)) {
            $pageUrl .= '?' . $pageQuery;
        }

        return $pageUrl;
    }
}
