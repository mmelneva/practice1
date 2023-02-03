<?php namespace App\Services\Repositories\CatalogCategory;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\ProductTypePage;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Attribute\PositionUpdater;
use App\Services\RepositoryFeatures\Tree\PublishedTreeBuilderInterface;

/**
 * Class EloquentCatalogCategoryRepository
 * @package App\Services\Repositories\CatalogCategory
 */
class EloquentCatalogCategoryRepository implements CatalogCategoryRepositoryInterface
{
    const POSITION_STEP = 10;

    /**
     * @var PublishedTreeBuilderInterface
     */
    private $treeBuilder;
    /**
     * @var EloquentAttributeToggler
     */
    private $attributeToggler;

    /**
     * @var PositionUpdater
     */
    private $positionUpdater;

    public function __construct(
        PublishedTreeBuilderInterface $treeBuilder,
        EloquentAttributeToggler $attributeToggler,
        PositionUpdater $positionUpdater
    ) {
        $this->treeBuilder = $treeBuilder;
        $this->attributeToggler = $attributeToggler;
        $this->positionUpdater = $positionUpdater;
    }

    /**
     * @inheritDoc
     */
    public function newInstance(array $data = [])
    {
        return new CatalogCategory($data);
    }

    /**
     * @inheritDoc
     */
    public function findById($id)
    {
        return CatalogCategory::find($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        if (empty($data['position'])) {
            $query = CatalogCategory::query();
            $this->treeBuilder->scopeChildOf($query, $data['parent_id']);
            $maxPosition = $query->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }
            $data['position'] = $maxPosition + self::POSITION_STEP;
        }

        return CatalogCategory::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data)
    {
        $category = $this->findById($id);
        if (!is_null($category)) {
            $category->fill($data);
            $category->save();
        }

        return $category;
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        $category = $this->findById($id);
        if (!is_null($category)) {
            $category->delete();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getIdsByProductIds(array $productIds)
    {
        $categoryIds = [];

        if (count($productIds) == 0) {
            $productIds[] = null;
        }

        $products = CatalogProduct::whereIn('id', $productIds)->groupBy('category_id')->get();

        /** @var CatalogProduct[] $products */
        foreach ($products as $product) {
            if (!is_null($product->category)) {
                $parents = $this->getTreePath($product->category->id);
                foreach ($parents as $c) {
                    $categoryIds[] = $c->id;
                }
                $categoryIds = array_unique($categoryIds);
            }

        }

        return $categoryIds;
    }

    /**
     * @inheritDoc
     */
    public function rootedElements($published = false)
    {
        $query = CatalogCategory::query();
        $this->treeBuilder->scopeRooted($query);
        $query->orderBy('position');
        if ($published) {
            $query->where('publish', true);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getTreePath($categoryId)
    {
        return $this->treeBuilder->getTreePath(new CatalogCategory(), $categoryId);
    }

    /**
     * @inheritDoc
     */
    public function getTree()
    {
        return $this->treeBuilder->getTree(new CatalogCategory());
    }

    /**
     * @inheritDoc
     */
    public function getCollapsedTree($categoryId = null)
    {
        return $this->treeBuilder->getCollapsedTree(new CatalogCategory(), $categoryId);
    }

    /**
     * @inheritDoc
     */
    public function getParentVariants($id, $root = null)
    {
        return $this->treeBuilder->getTreeVariants(new CatalogCategory(), $id, $root);
    }


    /**
     * @inheritDoc
     */
    public function updatePositions(array $positions)
    {
        $this->positionUpdater->updatePositions(new CatalogCategory(), $positions, self::POSITION_STEP);
    }

    /**
     * @inheritDoc
     */
    public function toggleAttribute($id, $attribute)
    {
        $category = $this->findById($id);
        $this->attributeToggler->toggleAttribute($category, $attribute);

        return $category;
    }

    /**
     * @inheritdoc
     */
    public function findByAlias($alias)
    {
        return CatalogCategory::where('alias', $alias)->first();
    }

    /**
     * @inheritDoc
     */
    public function findPublishedByAlias($alias)
    {
        return CatalogCategory::where('alias', $alias)->where('publish', true)->first();
    }

    /**
     * @inheritdoc
     */
    public function getIds($rootId = null)
    {
        return $this->treeBuilder->getIds(new CatalogCategory(), $rootId);
    }

    /**
     * @inheritDoc
     */
    public function getPublishedIds($rootId = null)
    {
        return $this->treeBuilder->getPublishedIds(new CatalogCategory(), $rootId);
    }

    /**
     * @inheritDoc
     */
    public function getCachedPublishedIds($rootId = null)
    {
        static $publishedIds;

        $cachedKey = is_null($rootId) ? 'all' : $rootId;
        if (!isset($publishedIds[$cachedKey])) {
            $publishedIds[$cachedKey] = $this->treeBuilder->getPublishedIds(new CatalogCategory(), $rootId);
        }

        return array_get($publishedIds, $cachedKey, []);
    }

    /**
     * @inheritDoc
     */
    public function checkPublishInTreeById($categoryId)
    {
        if (!is_null($categoryId)) {
            $allPublishedIds = $this->getCachedPublishedIds();
            if (in_array($categoryId, $allPublishedIds)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function findPublishedById($id)
    {
        return CatalogCategory::where('id', $id)->where('publish', true)->first();
    }

    /**
     * @inheritdoc
     */
    public function getCatalogTree()
    {
        $with = [];
        $with['children'] = function ($query) use (&$with) {
            $query->orderBy('position')->with($with);
        };
        $with['products'] = function ($query) {
            $query->orderBy('position');
        };

        return CatalogCategory::whereNull('parent_id')->orderBy('position')->with($with)->get();
    }

    /**
     * @inheritdoc
     */
    public function getManualCategoryForProductTypePage(ProductTypePage $productTypePage)
    {
        $category = $productTypePage->manualProductListCategory()->whereNull('parent_id')->first();
        if (is_null($category)) {
            $category = CatalogCategory::whereNull('parent_id')->orderBy('position')->first();
        }

        return $category;
    }

    /**
     * @param $query
     * @return mixed
     */
    private function scopePublished($query)
    {
        return $query->where('publish', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    private function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * @inheritdoc
     */
    public function getRoot($categoryId)
    {
        return $this->treeBuilder->getRoot($this->newInstance(), $categoryId);
    }

    /**
     * @inheritDoc
     */
    public function menuElements()
    {
        $query = CatalogCategory::query();
        $this->treeBuilder->scopeRooted($query);

        $query->orderBy('position');
        $query->where('publish', true);
        $query->where('top_menu', true);

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function findByIdOrFail($id)
    {
        return CatalogCategory::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getSiteMapTree()
    {
        return $this->treeBuilder->getTree(
            new CatalogCategory(),
            function ($query) {
                $this->treeBuilder->scopePublishedInLvl($query);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        $query = CatalogCategory::query();
        $this->scopeOrdered($query);
        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getIdListForAssociatedProduct(CatalogProduct $product)
    {
        return $product->associatedCategories->lists('id');
    }

    /**
     * @inheritDoc
     */
    public function getCategoriesVariants()
    {
        return $this->all()->lists('name', 'id');
    }
}
