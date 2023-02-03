<?php namespace App\Services\Repositories\ProductTypePage;

use App\Models\CatalogCategory;
use App\Models\Node;
use App\Models\ProductTypePage;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\RepositoryFeatures\Tree\PublishedTreeBuilderInterface;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;

class EloquentProductTypeRepository implements ProductTypePageRepositoryInterface
{
    private $publishedTreeBuilder;
    private $nodeRepository;
    private $catalogCategoryRepository;

    /**
     * @var EloquentAttributeToggler
     */
    private $attributeToggler;

    public function __construct(
        PublishedTreeBuilderInterface $publishedTreeBuilder,
        NodeRepositoryInterface $nodeRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        EloquentAttributeToggler $attributeToggler
    ) {
        $this->publishedTreeBuilder = $publishedTreeBuilder;
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->attributeToggler = $attributeToggler;
    }

    /**
     * @inheritdoc
     */
    public function findForNodeOrNew(Node $node)
    {
        $productTypePage = $node->productTypePage()->first();
        if (is_null($productTypePage)) {
            $productTypePage = new ProductTypePage();
            $productTypePage->node()->associate($node);
        }

        return $productTypePage;
    }

    public function all()
    {
        return ProductTypePage::join('nodes', 'nodes.id', '=', 'product_type_pages.node_id')
            ->where('nodes.type', StructureTypesServiceProvider::TYPE_PRODUCT_TYPE_PAGE)
            ->orderBy('nodes.position')
            ->select('product_type_pages.*')
            ->get();
    }

    public function associateProductTypePageToNode()
    {
        $nodes = $this->nodeRepository->allByType(StructureTypesServiceProvider::TYPE_PRODUCT_TYPE_PAGE);
        foreach ($nodes as $node) {
            if (is_null($node->productTypePage)) {
                $productTypePage = new ProductTypePage();
                $productTypePage->node()->associate($node);
                $productTypePage->save();
            }
        }
    }

    public function allPublishedWithFilter()
    {
        $query = $this->allPublishedQuery();

        return $query->where('product_type_pages.product_list_way', ProductTypePage::WAY_FILTERED)
            ->orderBy('nodes.position')
            ->get();
    }

    public function findPublished($id)
    {
        $query = $this->allPublishedQuery();
        $page = $query->where('product_type_pages.id', $id)->first();

        return $page;
    }

    private function allPublishedQuery()
    {
        $ids = $this->publishedTreeBuilder->getPublishedIds(new Node());
        if (count($ids) === 0) {
            $ids = [null];
        }

        // todo: вынести тип в ноду
        return ProductTypePage::join('nodes', 'nodes.id', '=', 'product_type_pages.node_id')
            ->whereIn('nodes.id', $ids)
            ->where('nodes.type', StructureTypesServiceProvider::TYPE_PRODUCT_TYPE_PAGE)
            ->select('product_type_pages.*');
    }

    public function getPopularOnHomePage()
    {
        return $this->allPublishedQuery()
            ->where('product_type_pages.in_popular', true)
            ->with('node')
            ->get()
            ->sortBy(
                function ($page) {
                    return $page->node->position;
                }
            );
    }

    /**
     * @inheritdoc
     */
    public function getTree()
    {
        $with = [];
        $with['children'] = function ($query) use (&$with) {
            $query->orderBy('position')->with($with);
        };

        return Node::whereNull('parent_id')->orderBy('position')->with($with)->get();
    }

    public function findById($id)
    {
        return ProductTypePage::find($id);
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

}
