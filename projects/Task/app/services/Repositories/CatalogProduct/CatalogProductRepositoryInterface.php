<?php namespace App\Services\Repositories\CatalogProduct;

use App\Models\CatalogProduct;
use App\Models\ProductTypePage;
use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;

/**
 * Interface CatalogProductRepositoryInterface
 * @package App\Services\Repositories\CatalogProduct
 */
interface CatalogProductRepositoryInterface extends CreateUpdateRepositoryInterface, ToggleableRepositoryInterface
{
    /**
     * Get new model instance.
     *
     * @param array $data
     * @return \App\Models\CatalogProduct
     */
    public function newInstance(array $data = []);

    /**
     * @param $id
     * @return \App\Models\CatalogProduct|null
     */
    public function findById($id);

    /**
     * Find product by id or fail.
     *
     * @param $id
     * @return \App\Models\CatalogCategory|null
     */
    public function findByIdOrFail($id);

    /**
     * @param $id
     * @return \App\Models\CatalogProduct|null
     */
    public function findPublishedById($id);

    /**
     * Delete product by id.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Select list of products in category.
     *
     * @param $categoryId
     * @return mixed
     */
    public function allInCategory($categoryId);

    /**
     * Get productList and previousItemsCount in category by page.
     * @param $categoryId
     * @param int $onPage
     * @return array ['productList' => $productList, 'previousItemsCount' => $previousItemsCount]
     */
    public function allInCategoryByPage($categoryId, $onPage = 25);

    /**
     * Update positions.
     *
     * @param array $positions
     * @return mixed
     */
    public function updatePositions(array $positions);

     /**
     * Get list of available variants for filter.
     *
     * @param null $rootCategoryId
     * @param array $filterData
     * @return mixed
     */
    public function filterVariants($rootCategoryId = null, $filterData = []);

    /**
     * Clear filter variants - will return only applicable variants.
     *
     * @param null $rootCategoryId
     * @param array $filterData
     * @return array
     */
    public function clearFilterVariants($rootCategoryId = null, $filterData = []);

    /**
     * Get list of published products inside category and children of it with filter products selection.
     * Will return slice of all the variants according to page and limit.
     *
     * @param null $rootCategoryId
     * @param int $page
     * @param int $limit
     * @param null $sorting
     * @param array $filterData
     * @return mixed
     */
    public function allPublishedInTreeByPage(
        $rootCategoryId = null,
        $page = 1,
        $limit = 8,
        $sorting = null,
        $filterData = []
    );

    /**
     * Get list of published products inside category for product type page with filter products selection.
     * Will return slice of all the variants according to page and limit.
     *
     * @param $rootCategoryId
     * @param ProductTypePage $productTypePage
     * @param int $page
     * @param int $limit
     * @param null $sorting
     * @param array $filterData
     * @return mixed
     */
    public function allPublishedInTreeForTypeByPage(
        $rootCategoryId,
        ProductTypePage $productTypePage,
        $page = 1,
        $limit = 8,
        $sorting = null,
        $filterData = []
    );

    /**
     * Get list of published products inside cateogory for product type page with manual products selection.
     * Will return slice of all the variants according to page and limit.
     *
     * @param ProductTypePage $productTypePage
     * @param int $page
     * @param int $limit
     * @param null $sorting
     * @return mixed
     */
    public function allPublishedInTreeForManualTypeByPage(
        ProductTypePage $productTypePage,
        $page = 1,
        $limit = 8,
        $sorting = null
    );

    /**
     * Get list of filtered products, which sorted according to product type page.
     *
     * @param null $rootCategoryId
     * @param ProductTypePage $productTypePage
     * @param null $sorting
     * @param array $filterData
     * @param bool $use_published
     * @return mixed
     */
    public function allFilteredForProductType($rootCategoryId = null, ProductTypePage $productTypePage, $sorting = null, $filterData = [], $use_published=false);

    /**
     * Compare filter data.
     *
     * @param $rootCategoryId
     * @param $baseFilterData - base filter data.
     * @param $filterData - filter data which we check to be equal with base filter data.
     * @return mixed
     */
    public function compareFilterData($rootCategoryId, $baseFilterData, $filterData);

    /**
     * Get available sort variants.
     *
     * @return array
     */
    public function sortingVariants();

    /**
     * Check is sort values set and valid
     * @param $sort
     * @return mixed
     */
    public function checkSortVariant($sort);

    /**
     * Get built_in variants
     * @return mixed
     */
    public function getBuiltInVariants();

    /**
     * Find product by id or get new.
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findByIdOrNew($id, $columns = array('*'));


    /**
     * Fill product with data and save
     *
     * @param CatalogProduct $product
     * @param array $data
     * @return mixed
     */
    public function fillAndSave(CatalogProduct $product, array $data);

    /**
     * Update all products
     *
     * @param array $data
     * @return mixed
     */
    public function updateAll(array $data);

    /**
     * Get ids of published products
     * @return array
     */
    public function getPublishedIds();

    /**
     * Get last created published products - limit 15
     *
     * @return mixed
     */
    public function getLastPublishedProducts();


    /**
     * Get list of published products in categories ids list.
     *
     * @param array $categoriesIds
     * @return mixed
     */
    public function getPublishedInCategoriesIds(array $categoriesIds);

    /**
     * Get published products this category (by category_id) with same string attribute value
     * (exclude product with id = $productId)
     *
     * @param CatalogProduct $product
     * @param $attributeId
     * @param $attrValue
     * @return mixed
     */
    public function getSimilarPublishedByStringAttribute(CatalogProduct $product, $attributeId, $attrValue);

    /**
     * Get published products this category (by category_id) with same list attribute allowed value id
     * (exclude product with id = $productId)
     *
     * @param CatalogProduct $product
     * @param $attributeId
     * @param $attrValueId
     * @return mixed
     */
    public function getSimilarPublishedByListAttribute(CatalogProduct $product, $attributeId, $attrValueId);

    /**
     * Get published products in this category (by category_id) with multiple values attribute allowed value id in $attrValueIds
     * (exclude product with id = $productId)
     *
     * @param CatalogProduct $product
     * @param $attributeId
     * @param array $attrValueIds
     * @return mixed
     */
    public function getSimilarPublishedByMultipleValuesAttribute(CatalogProduct $product, $attributeId, array $attrValueIds);

    public function getVariants();

    public function getAllByIds($ids = []);
}
