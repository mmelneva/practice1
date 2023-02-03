<?php namespace App\Services\Catalog\FilterUrlParser;

use App\Services\Catalog\FilterUrlParser\Exception\IncorrectCategory;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterData;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterUrl;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

/**
 * Class FilterUrlParser
 * Parser for filter url.
 *
 * @package App\Services\Catalog\FilterUrlParser
 */
class FilterUrlParser
{
    private $catalogCategoryRepository;
    private $catalogProductRepository;

    public function __construct(
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository
    ) {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
    }

    /**
     * Parse filter url.
     *
     * @param string $filterUrl
     * @return array - contains category alias and filter data.
     */
    public function parseFilterUrl($filterUrl)
    {
        $parsedUrl = parse_url($filterUrl);

        $path = array_get($parsedUrl, 'path');
        $query = array_get($parsedUrl, 'query');


        $expPath = explode('/', $path);
        $categoryAlias = $expPath[count($expPath) - 1];

        parse_str($query, $queryData);
        $filterData = array_get($queryData, 'filter', []);
        if (!is_array($filterData)) {
            $filterData = null;
        }

        $sort = array_get($queryData, 'sort');

        return [$categoryAlias, $filterData, $sort];
    }

    /**
     * Get filter data from url.
     *
     * @param string $filterUrl
     * @return array - contains category object and filter data.
     * @throws IncorrectFilterUrl
     */
    public function getFilterDataFromUrl($filterUrl)
    {
        list($categoryAlias, $filterData, $sort) = $this->parseFilterUrl($filterUrl);

        if (!is_array($filterData)) {
            throw new IncorrectFilterData();
        }
        $category = $this->catalogCategoryRepository->findByAlias($categoryAlias);

        if (is_null($category)) {
            throw new IncorrectCategory();
        }

        return [$category, $filterData, $sort];
    }

    public function getFilterDataWithoutCategoryFromUrl($filterUrl)
    {
        list($categoryAlias, $filterData, $sort) = $this->parseFilterUrl($filterUrl);

        if (!is_array($filterData)) {
            throw new IncorrectFilterData();
        }

        return [$filterData, $sort];
    }
}
