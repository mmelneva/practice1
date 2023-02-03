<?php namespace App\Services\Repositories\CatalogProduct;

use App\Models\CatalogProduct;
use App\Models\ProductBuiltInConstants;
use App\Models\ProductTypePage;
use App\Services\Catalog\Filter\Filter\FilterInterface;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\AttributeValue\AttributeValueRepositoryInterface;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Attribute\PositionUpdater;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentCatalogProductRepository
 * @package App\Services\Repositories\CatalogProduct
 */
class EloquentCatalogProductRepository implements CatalogProductRepositoryInterface
{
    const POSITION_STEP = 10;
    const LAST_PRODUCTS_LIMIT = 15;
    const SIMILAR_PRODUCTS_LIMIT = 15;

    /**
     * @var EloquentAttributeToggler
     */
    private $attributeToggler;

    /**
     * @var PositionUpdater
     */
    private $positionUpdater;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @var AttributeValueRepositoryInterface
     */
    private $attributeValueRepository;

    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @param EloquentAttributeToggler $attributeToggler
     * @param PositionUpdater $positionUpdater
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     * @param FilterInterface $filter
     */
    public function __construct(
        EloquentAttributeToggler $attributeToggler,
        PositionUpdater $positionUpdater,
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        FilterInterface $filter
    ) {
        $this->attributeToggler = $attributeToggler;
        $this->positionUpdater = $positionUpdater;
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->filter = $filter;
    }


    public function newInstance(array $data = [])
    {
        return new CatalogProduct($data);
    }


    public function findById($id)
    {
        return CatalogProduct::find($id);
    }


    public function findByIdOrFail($id)
    {
        return CatalogProduct::findOrFail($id);
    }

    public function findPublishedById($id)
    {
        /** @var CatalogProduct $product */
        $product = CatalogProduct::where('publish', true)->find($id);

        if (!is_null($product)) {
            $publishedCategoriesIds = $this->catalogCategoryRepository->getCachedPublishedIds();

            $relatedCategories = $product->associatedCategories()->lists('catalog_categories.category_id');
            $relatedCategories[] = $product->category_id;

            $publishedProductCategoriesIds = array_intersect($publishedCategoriesIds, $relatedCategories);
            if (count($publishedProductCategoriesIds) == 0) {
                return null;
            }
        }

        return $product;
    }

    public function create(array $data)
    {
        if (empty($data['position'])) {
            $maxPosition = CatalogProduct::where('category_id', $data['category_id'])->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }
            $data['position'] = $maxPosition + self::POSITION_STEP;
        }

        return CatalogProduct::create($data);
    }


    public function update($id, array $data)
    {
        $product = $this->findById($id);
        if (!is_null($product)) {
            $this->fillAndSave($product, $data);
        }

        return $product;
    }

    public function fillAndSave(CatalogProduct $product, array $data)
    {
        $product->fill($data);
        $product->save();
    }


    public function delete($id)
    {
        $product = $this->findById($id);
        if (!is_null($product)) {
            $product->delete();

            return true;
        } else {
            return false;
        }
    }


    public function toggleAttribute($id, $attribute)
    {
        $product = $this->findById($id);
        $this->attributeToggler->toggleAttribute($product, $attribute);

        return $product;
    }

    public function allInCategory($categoryId)
    {
        return $this->allInCategoryQuery($categoryId)->get();
    }

    public function allInCategoryByPage($categoryId, $onPage = 25)
    {
        $productList = $this->allInCategoryQuery($categoryId)->paginate($onPage);
        $previousItemsCount = ($productList->getCurrentPage() - 1) * $productList->getPerPage();

        return [
            'productList' => $productList,
            'previousItemsCount' => $previousItemsCount,
        ];
    }

    private function allInCategoryQuery($categoryId)
    {
        $query = CatalogProduct::join(
            'product_category_associations',
            'product_category_associations.product_id',
            '=',
            'catalog_products.id'
        )
            ->where('product_category_associations.category_id', $categoryId)
            ->select('catalog_products.*')
            ->distinct();

        return $this->scopeOrdered($query);
    }


    public function updatePositions(array $positions)
    {
        $this->positionUpdater->updatePositions(new CatalogProduct(), $positions, self::POSITION_STEP);
    }

    /**
     * Use with select('catalog_products.*') and distinct()
     * Get query to select published products in published category.
     *
     * @param $rootCategoryId
     * @return mixed
     */
    private function getPublishedInTreeQuery($rootCategoryId = null)
    {
        $categoryIdList = $this->catalogCategoryRepository->getCachedPublishedIds($rootCategoryId);
        if (count($categoryIdList) == 0) {
            $categoryIdList = [null];
        }

        $query = $this->getProductCategoryAssociationsQuery($categoryIdList);

        return $query->where('catalog_products.publish', true);
    }

    /**
     * Get query to select products in category.
     *
     * @param null $rootCategoryId
     * @return mixed
     */
    private function getAllInTreeQuery($rootCategoryId = null)
    {
        $categoryIdList = $this->catalogCategoryRepository->getIds($rootCategoryId);
        if (count($categoryIdList) == 0) {
            $categoryIdList = [null];
        }

        return $this->getProductCategoryAssociationsQuery($categoryIdList);
    }

    private function getProductCategoryAssociationsQuery(array $categoryIdList)
    {
        return CatalogProduct::join(
            'product_category_associations',
            'product_category_associations.product_id',
            '=',
            'catalog_products.id'
        )
            ->whereIn('product_category_associations.category_id', $categoryIdList);
    }

    public function filterVariants($rootCategoryId = null, $filterData = [])
    {
        if (!is_array($filterData)) {
            $filterData = [];
        }
        $query = $this->getPublishedInTreeQuery($rootCategoryId)
            ->select('catalog_products.*')
            ->distinct();

        return $this->filter->getVariants($query, $filterData);
    }

    public function clearFilterVariants($rootCategoryId = null, $filterData = [])
    {
        if (!is_array($filterData)) {
            $filterData = [];
        }
        $query = $this->getPublishedInTreeQuery($rootCategoryId)
            ->select('catalog_products.*')
            ->distinct();

        return $this->filter->clearFilterData($query, $filterData);
    }


    public function allPublishedInTreeByPage(
        $rootCategoryId = null,
        $page = 1,
        $limit = 8,
        $sorting = null,
        $filterData = []
    ) {
        $query = $this->getPublishedInTreeQuery($rootCategoryId);
        $this->filter->modifyQuery($query, $filterData);

        $selectQuery = clone $query;
        $countQuery = clone $query;

        // sorting should be after modify query, because otherwise some filters could work not correctly
        $this->modifyQueryWithSorting($selectQuery, $sorting);
        $products = $this->selectLimitedProducts($selectQuery, $page, $limit);
        $total = $this->selectProductCount($countQuery);

        return [
            'items' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    public function allPublishedInTreeForTypeByPage(
        $rootCategoryId,
        ProductTypePage $productTypePage,
        $page = 1,
        $limit = 8,
        $sorting = null,
        $filterData = []
    )
    {
        $productTypePageId = $productTypePage->id;
        $query = $this->getPublishedInTreeQuery($rootCategoryId);
        $query->leftJoin(
            'product_type_page_associations',
            function ($join) use ($productTypePageId) {
                $join->on(
                    'product_type_page_associations.catalog_product_id',
                    '=',
                    'catalog_products.id'
                )->where('product_type_page_associations.product_type_page_id', '=', $productTypePageId);
            }
        );

        $this->filter->modifyQuery($query, $filterData);

        $selectQuery = clone $query;
        $countQuery = clone $query;

        // sorting should be after modify query, because otherwise some filters could work not correctly
        //$this->modifyQueryWithSorting($selectQuery, $sorting, true);
        if (
            empty($sorting) &&
            $productTypePage->use_sort_scheme &&
            $productTypePage->sort_scheme
        ) {
            $this->modifyQueryWithSorting($selectQuery, 'random', true, $productTypePage->sort_scheme);
        } else {
            $this->modifyQueryWithSorting($selectQuery, $sorting, true);
        }

        $products = $this->selectLimitedProducts($selectQuery, $page, $limit);
        $total = $this->selectProductCount($countQuery);

        return [
            'items' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    public function allPublishedInTreeForManualTypeByPage(
        ProductTypePage $productTypePage,
        $page = 1,
        $limit = 8,
        $sorting = null
    ) {
        $query = $this->getPublishedInTreeQuery();
        $query->join(
            'product_type_page_associations',
            'product_type_page_associations.catalog_product_id',
            '=',
            'catalog_products.id'
        )->where('product_type_page_associations.manual', true)
            ->where('product_type_page_associations.product_type_page_id', $productTypePage->id);


        $selectQuery = clone $query;
        $countQuery = clone $query;


        //$this->modifyQueryWithSorting($selectQuery, $sorting, true);
        if (
            empty($sorting) &&
            $productTypePage->use_sort_scheme &&
            $productTypePage->sort_scheme
        ) {
            $this->modifyQueryWithSorting($selectQuery, 'random', true, $productTypePage->sort_scheme);
        } else {
            $this->modifyQueryWithSorting($selectQuery, $sorting, true);
        }
        $products = $this->selectLimitedProducts($selectQuery, $page, $limit);
        $total = $this->selectProductCount($countQuery);


        return [
            'items' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * Modify query to make it sorted.
     *
     * @param $query
     * @param $sorting
     * @param $productType
     */
    private function modifyQueryWithSorting($query, $sorting, $productType = false, $sortScheme = false)
    {
        switch ($sorting) {
            case 'price_asc':
                $query->orderByRaw("(ISNULL(price) OR price=0) ASC");
                $query->orderBy('price', 'ASC');
                break;
            case 'price_desc':
                $query->orderByRaw("(ISNULL(price) OR price=0) ASC");
                $query->orderBY('price', 'DESC');
                break;
            case 'random':
                if ($productType && $sortScheme) {
                    $query->orderByRaw("IF(FIELD(catalog_products.id, {$sortScheme})=0,1,0),FIELD(catalog_products.id, {$sortScheme})");
                    $this->scopeProductTypeOrdered($query);
                }
                break;
            default:
                if ($productType) {
                    $this->scopeProductTypeOrdered($query);
                } else {
                    $this->scopeOrdered($query);
                }
                break;
        }
    }

    /**
     * Select list of products.
     *
     * @param $query
     * @param $page
     * @param $limit
     * @return mixed
     */
    private function selectLimitedProducts($query, $page, $limit)
    {
        $products = $query
            ->select('catalog_products.*')->distinct()
            ->skip($limit * ($page - 1))->take($limit)
            ->get();

        return $products;
    }

    /**
     * Select total amounts of products.
     *
     * @param $query
     * @return mixed
     */
    private function selectProductCount($query)
    {
        $total = $query
            ->select(\DB::raw('COUNT(DISTINCT(catalog_products.id)) AS count'))
            ->pluck('count');

        return $total;
    }

    private function scopeOrdered($query)
    {
        return $query->orderBy('catalog_products.position');
    }

    private function scopeProductTypeOrdered($query)
    {
        return $query->orderByRaw("ISNULL(product_type_page_associations.position) ASC")
            ->orderBy('product_type_page_associations.position');
    }

    public function allFilteredForProductType(
        $rootCategoryId = null,
        ProductTypePage $productTypePage,
        $sorting = null,
        $filterData = [],
        $use_published=false
    ) {
        $productTypePageId = $productTypePage->id;

        $query = $this->getAllInTreeQuery($rootCategoryId);
        $query->select('catalog_products.*')->distinct();

        $query->leftJoin(
            'product_type_page_associations',
            function ($join) use ($productTypePageId) {
                $join->on(
                    'product_type_page_associations.catalog_product_id',
                    '=',
                    'catalog_products.id'
                )->where('product_type_page_associations.product_type_page_id', '=', $productTypePageId);
            }
        );

        $this->filter->modifyQuery($query, $filterData);

        // sorting should be after modify query, because otherwise some filters could work not correctly
        //$this->modifyQueryWithSorting($query, $sorting, true);
        if (
            empty($sorting) &&
            $productTypePage->use_sort_scheme &&
            $productTypePage->sort_scheme
        ) {
            $this->modifyQueryWithSorting($query, 'random', true, $productTypePage->sort_scheme);
        } else {
            $this->modifyQueryWithSorting($query, $sorting, true);
        }

        if($use_published){
            $query->where('publish', true);
        }
        return $query->get();
    }

    public function compareFilterData($rootCategoryId, $baseFilterData, $filterData)
    {
        if (!is_array($filterData)) {
            $filterData = [];
        }

        if (!is_array($baseFilterData)) {
            $baseFilterData = [];
        }

        $query = $this->getPublishedInTreeQuery($rootCategoryId);
        //todo: add if needed
//            ->select('catalog_products.*')
//            ->distinct();

        return $this->filter->compareFilterData(
            $query,
            $baseFilterData,
            $filterData
        );
    }

    public function sortingVariants()
    {
        return [
            'По популярности' => 'popular',
            'По цене (сначала дешёвые)' => 'price_asc',
            'По цене (сначала дорогие)' => 'price_desc',
        ];
    }

    public function checkSortVariant($sort)
    {
        $sortingVariants = $this->sortingVariants();

        if (!is_null($sort) && in_array($sort, $sortingVariants)) {
            return true;
        }

        return false;
    }

    public function getBuiltInVariants()
    {
        $variants = [];

        foreach (ProductBuiltInConstants::getConstants() as $c) {
            $variants[$c] = trans("validation.model_attributes.catalog_product.built_in.{$c}");
        }

        return $variants;
    }

    public function findByIdOrNew($id, $columns = array('*'))
    {
        return CatalogProduct::findOrNew($id, $columns);
    }

    public function updateAll(array $data)
    {
        if (!empty($data)) {
            $query = CatalogProduct::query();

            return $query->update($data);
        }

        return [];
    }

    public function getPublishedIds()
    {
        return $this->getPublishedInTreeQuery()
            ->select('catalog_products.*')
            ->distinct()
            ->lists('id');
    }

    public function getLastPublishedProducts()
    {
        $query = $this->getPublishedInTreeQuery()
            ->join(
                'product_home_page_associations',
                "product_home_page_associations.catalog_product_id",
                '=',
                "catalog_products.id"
            )
            ->orderBy('product_home_page_associations.position')
            ->orderBy('catalog_products.created_at', 'DECS')
            ->select('catalog_products.*')
            ->distinct()
            ->limit(self::LAST_PRODUCTS_LIMIT);

        //$sql = $query->toSql();

        return $query->get();
    }

    public function getPublishedInCategoriesIds(array $categoriesIds)
    {
        if (count($categoriesIds) > 0) {
            $query = $this->getProductCategoryAssociationsQuery($categoriesIds);

            return $query->where('catalog_products.publish', true)
                ->select('catalog_products.*')
                ->distinct()
                ->get();
        }

        return Collection::make([]);
    }

    public function getSimilarPublishedByStringAttribute(CatalogProduct $product, $attributeId, $attrValue)
    {
        $attrValue = trim($attrValue);
        if (empty($attrValue)) {
            return Collection::make([]);
        }

        $query = $this->getPublishedInTreeQuery($product->category_id);

        $query->join(
            "attribute_values",
            function ($join) use ($attributeId) {
                $join->on(
                    'catalog_products.id',
                    '=',
                    "attribute_values.product_id"
                )->where("attribute_values.attribute_id", '=', $attributeId);
            }
        )
            ->where("attribute_values.value", $attrValue)
            ->where('catalog_products.id', '<>', $product->id);

        $this->scopeOrdered($query);

        return $query->select('catalog_products.*')
            ->distinct()
            ->limit(self::SIMILAR_PRODUCTS_LIMIT)
            ->get();
    }

    public function getSimilarPublishedByListAttribute(CatalogProduct $product, $attributeId, $attrValueId)
    {
        $query = $this->getPublishedInTreeQuery($product->category_id);

        $query->leftJoin(
            "attribute_values",
            function ($join) use ($attributeId) {
                $join->on(
                    'catalog_products.id',
                    '=',
                    "attribute_values.product_id"
                )->where("attribute_values.attribute_id", '=', $attributeId);
            }
        )
            ->where("attribute_values.allowed_value_id", $attrValueId)
            ->where('catalog_products.id', '<>', $product->id);

        $this->scopeOrdered($query);

        return $query->select('catalog_products.*')
            ->distinct()
            ->limit(self::SIMILAR_PRODUCTS_LIMIT)
            ->get();
    }

    public function getSimilarPublishedByMultipleValuesAttribute(CatalogProduct $product, $attributeId, array $attrValueIds)
    {
        if (count($attrValueIds) == 0) {
            return Collection::make([]);
        }

        $query = $this->getPublishedInTreeQuery($product->category_id);

        $query->leftJoin(
            "attribute_values",
            function ($join) use ($attributeId) {
                $join->on(
                    'catalog_products.id',
                    '=',
                    "attribute_values.product_id"
                )->where("attribute_values.attribute_id", '=', $attributeId);
            }
        )->leftJoin(
            "attribute_multiple_values",
            "attribute_multiple_values.attribute_value_id",
            '=',
            "attribute_values.id"
        )
            ->whereIn("attribute_multiple_values.allowed_value_id", $attrValueIds)
            ->where('catalog_products.id', '<>', $product->id);

        $this->scopeOrdered($query);

        return $query->select('catalog_products.*')
            ->distinct()
            ->limit(self::SIMILAR_PRODUCTS_LIMIT)
            ->get();
    }

    public function getVariants()
    {
        $variants = [null => 'не выбрано'];
        $dbVariants = CatalogProduct::all()->lists('name', 'id');
        return $variants + $dbVariants;
    }

    public function getAllByIds($ids = [])
    {
        $categoryIdList = $this->catalogCategoryRepository->getPublishedIds();
        if (count($categoryIdList) == 0) {
            $categoryIdList = [null];
        }

        $products = CatalogProduct::whereIn('category_id', $categoryIdList)
            ->whereIn('id', $ids)
            ->where('publish', true)
            ->get();

        $ret = [];
        foreach ($products as $product) {
            $ret[$product->id] = $product;
        }

        return $ret;
    }


    public function getPublishedProductsCountDataForManualProductTypePage(
            ProductTypePage $productTypePage,
            $additional = null
    ) {
        $query = $this->getCachedPublishedQuery();
        $query->join(
                'product_type_page_associations',
                'product_type_page_associations.catalog_product_id',
                '=',
                'catalog_products.id'
        )->where('product_type_page_associations.manual', true)
                ->where('product_type_page_associations.product_type_page_id', $productTypePage->id);

        $published_query= clone $query;
        $published_query->where('catalog_products.publish', true);

        return [
                'total' =>  $this->selectProductCount($query),
                'published' =>  $this->selectProductCount($published_query)
        ];

    }

    private function getCachedPublishedQuery()
    {
        static $publishedQuery;
        if (is_null($publishedQuery)) {
            $categoryIdList = $this->catalogCategoryRepository->getCachedPublishedIds();
            if (count($categoryIdList) == 0) {
                $categoryIdList = [null];
            }

            $publishedQuery = CatalogProduct::whereIn('category_id', $categoryIdList)
                    ->where('catalog_products.publish', true)
                    ->with('category');
        }

        return clone $publishedQuery;
    }

    /**
     * Modify query according to current additional value.
     *
     * @param $query
     * @param $additionalValue
     * @return mixed
     */
    private function modifyQueryWithAdditionalFilter($query, $additionalValue)
    {
        echo "additionalValue=$additionalValue\n";
        switch ($additionalValue) {
            case 'new':
                $query->where("catalog_products.new", true);
                break;
            case 'discount':
//                $this->scopeAvailable($query);
                $query->where('catalog_products.old_price', '>', '0')
                        ->whereRaw('catalog_products.price < catalog_products.old_price');
                break;
        }

        return $query;
    }

}
