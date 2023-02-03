<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Controllers\Client\CatalogController\CatalogCategoriesSubController;
use App\Controllers\Client\CatalogController\CatalogProductsSubController;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;

class CatalogController extends BaseController
{
    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /**
     * @var CatalogCategoriesSubController
     */
    private $catalogCategoriesSubController;

    /**
     * @var CatalogProductsSubController
     */
    private $catalogProductsSubController;

    /**
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param CatalogCategoriesSubController $catalogCategoriesSubController
     * @param CatalogProductsSubController $catalogProductsSubController
     */
    public function __construct(
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogCategoriesSubController $catalogCategoriesSubController,
        CatalogProductsSubController $catalogProductsSubController
    ) {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;

        $this->catalogCategoriesSubController = $catalogCategoriesSubController;
        $this->catalogProductsSubController = $catalogProductsSubController;
    }

    public function getShow($catalogQuery)
    {
        $parsedQuery = $this->parseCatalogQuery($catalogQuery);
        $page = $parsedQuery['page'];
        $lastAlias = array_get($parsedQuery['alias_path'], count($parsedQuery['alias_path']) - 1);

        if (preg_match('/^tovar-(\d+)$/', $lastAlias, $aliasMatches)) {
            $catalogItem = $this->catalogProductRepository->findPublishedById($aliasMatches[1]);
            if (is_null($catalogItem)) {
                \App::abort(404, 'Incorrect path to product item');
            }
            $parentId = $catalogItem->category_id;
        } else {
            $catalogItem = $this->catalogCategoryRepository->findPublishedByAlias($lastAlias);
            if (is_null($catalogItem)) {
                \App::abort(404, 'Incorrect path to product item');
            }
            $parentId = object_get($catalogItem, 'parent_id');
        }

        $categoryPath = $this->getCategoryPath($parsedQuery['alias_path'], $catalogItem instanceof CatalogCategory);
        $lastCategory = end($categoryPath);
        $lastCategoryId = $lastCategory ? $lastCategory->id : null;

        if ($lastCategoryId !== $parentId) {
            \App::abort(404, 'Incorrect path to catalog item');
        }


        if ($catalogItem instanceof CatalogProduct) {
            if (!is_null($page)) {
                \App::abort(404, 'Page is not allowed for product');
            }

            return $this->catalogProductsSubController->getCatalogProductResponse($catalogItem);
        } elseif ($catalogItem instanceof CatalogCategory) {
            if ($page == 1) {
                \App::abort(404, 'Page 1 is not allowed in url');
            }

            return $this->catalogCategoriesSubController->getCatalogCategoryResponse(
                $catalogItem,
                is_null($page) ? 1 : $page
            );

        } else {
            \App::abort(404, 'Unknown type for catalog item.');

            return null;
        }
    }

    /**
     * Get full catalog
     * @param $pageQuery
     * @return $this
     */
    public function getIndex($pageQuery = null)
    {
        if (preg_match('/^page-([1-9]\d*)$/', $pageQuery, $matches)) {
            $page = $matches[1];
        } else {
            $page = null;
        }

        if ($page == 1) {
            \App::abort(404, 'Page 1 is not allowed in url');
        }

        return $this->catalogCategoriesSubController->getCatalogResponse(
            is_null($page) ? 1 : $page
        );
    }

    /**
     * Get category path.
     *
     * @param $aliasPath
     * @param bool|true $checkPublish
     * @return array
     */
    private function getCategoryPath($aliasPath, $checkPublish = true)
    {
        $categoryPath = [];
        $lastCategory = null;
        for ($i = 0; $i < count($aliasPath) - 1; $i += 1) {
            $alias = $aliasPath[$i];
            if ($checkPublish) {
                $category = $this->catalogCategoryRepository->findPublishedByAlias($alias);
            } else {
                $category = $this->catalogCategoryRepository->findByAlias($alias);
            }
            if (is_null($category) || !is_null($lastCategory) && $category->parent_id != $lastCategory->id) {
                \App::abort(404, 'Wrong category in path');
            }

            $lastCategory = $category;
            $categoryPath[] = $lastCategory;
        }

        return $categoryPath;
    }

    /**
     * Parse catalog query.
     * Get alias path and page.
     *
     * @param $catalogQuery
     * @return array
     */
    private function parseCatalogQuery($catalogQuery)
    {
        $queryArray = explode('/', $catalogQuery);

        $lastIndex = count($queryArray) - 1;
        if (preg_match('/^page-([1-9]\d*)$/', $queryArray[$lastIndex], $matches)) {
            $page = $matches[1];
            unset($queryArray[$lastIndex]);
        } else {
            $page = null;
        }

        return [
            'alias_path' => $queryArray,
            'page' => $page,
        ];
    }
}
