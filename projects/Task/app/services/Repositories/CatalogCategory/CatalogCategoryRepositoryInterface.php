<?php namespace App\Services\Repositories\CatalogCategory;

use App\Models\CatalogProduct;
use App\Models\ProductTypePage;
use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;

/**
 * Interface CatalogCategoryRepositoryInterface
 * @package App\Services\Repositories\CatalogCategory
 */
interface CatalogCategoryRepositoryInterface extends CreateUpdateRepositoryInterface, ToggleableRepositoryInterface
{
    /**
     * Get new model instance.
     *
     * @param array $data
     * @return \App\Models\CatalogCategory
     */
    public function newInstance(array $data = []);

    /**
     * Find category by id.
     *
     * @param $id
     * @return \App\Models\CatalogCategory|null
     */
    public function findById($id);

    /**
     * Get list of parents.
     *
     * @param $categoryId
     * @return mixed
     */
    public function getTreePath($categoryId);

    /**
     * Get tree.
     *
     * @return mixed
     */
    public function getTree();

    /**
     * Get collapsed tree.
     *
     * @param $categoryId
     * @return mixed
     */
    public function getCollapsedTree($categoryId = null);

    /**
     * Update positions.
     *
     * @param array $positions
     * @return mixed
     */
    public function updatePositions(array $positions);

    /**
     * Get parent variants.
     *
     * @param $id
     * @param null $root
     * @return array
     */
    public function getParentVariants($id, $root = null);

    /**
     * Delete the category.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Get category id list by product id list.
     *
     * @param array $productIds
     * @return mixed
     */
    public function getIdsByProductIds(array $productIds);

    /**
     * Get rooted elements.
     *
     * @param bool $published
     * @return mixed
     */
    public function rootedElements($published = false);

    /**
     * Find category by alias.
     *
     * @param $alias
     * @return \App\Models\CatalogCategory|null
     */
    public function findByAlias($alias);

    /**
     * Find published category by alias.
     *
     * @param $alias
     * @return \App\Models\CatalogCategory|null
     */
    public function findPublishedByAlias($alias);

    /**
     * Get list of ids.
     *
     * @param null $rootId
     * @return mixed
     */
    public function getIds($rootId = null);

    /**
     * Get list of published ids.
     *
     * @param int|null $rootId
     * @return array
     */
    public function getPublishedIds($rootId = null);

    /**
     * Get cached published categories ids for $rootId category
     * @param null $rootId
     * @return mixed
     */
    public function getCachedPublishedIds($rootId = null);

    /**
     * Check if category published in tree (all cached published ids) by category id
     *
     * @param $categoryId
     * @return mixed
     */
    public function checkPublishInTreeById($categoryId);

    /**
     * Find published category by id.
     *
     * @param $id
     * @return \App\Models\CatalogCategory|null
     */
    public function findPublishedById($id);

    /**
     * Get tree of categories and products.
     *
     * @return mixed
     */
    public function getCatalogTree();

    /**
     * Get category for product type page.
     *
     * @param ProductTypePage $productTypePage
     * @return \App\Models\CatalogCategory|null
     */
    public function getManualCategoryForProductTypePage(ProductTypePage $productTypePage);

    /**
     * Get root category.
     *
     * @param $categoryId
     * @return mixed
     */
    public function getRoot($categoryId);

    /**
     * Get menu elements.
     *
     * @return mixed
     */
    public function menuElements();

    /**
     * Find category by id or fail.
     *
     * @param $id
     * @return \App\Models\CatalogCategory|null
     */
    public function findByIdOrFail($id);

    /**
     * Get tree for siteMap
     *
     * @return mixed
     */
    public function getSiteMapTree();

    /**
     * Get ordered all categories
     * @return mixed
     */
    public function all();

    /**
     * Get categories id associated with product
     * @param CatalogProduct $product
     * @return mixed
     */
    public function getIdListForAssociatedProduct(CatalogProduct $product);

    /**
     * Get all categories variants ['category_id' => 'category name']
     * @return mixed
     */
    public function getCategoriesVariants();
}
