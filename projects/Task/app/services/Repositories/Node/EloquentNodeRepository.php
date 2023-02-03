<?php namespace App\Services\Repositories\Node;

use App\Controllers\Client\HomePagesController;
use App\Models\Node;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Attribute\PositionUpdater;
use App\Services\RepositoryFeatures\Order\OrderScopesInterface;
use App\Services\RepositoryFeatures\Tree\PublishedTreeBuilderInterface;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentNodeRepository
 * @package App\Services\Repositories\Node
 */
class EloquentNodeRepository implements NodeRepositoryInterface
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
     * @var OrderScopesInterface
     */
    private $orderScope;
    /**
     * @var PositionUpdater
     */
    private $positionUpdater;
    /**
     * @var PossibleVariants
     */
    private $possibleVariants;

    /**
     * @param OrderScopesInterface $orderScope
     * @param PublishedTreeBuilderInterface $treeBuilder
     * @param EloquentAttributeToggler $attributeToggler
     * @param PositionUpdater $positionUpdater
     * @param PossibleVariants $possibleVariants
     */
    public function __construct(
        OrderScopesInterface $orderScope,
        PublishedTreeBuilderInterface $treeBuilder,
        EloquentAttributeToggler $attributeToggler,
        PositionUpdater $positionUpdater,
        PossibleVariants $possibleVariants
    ) {
        $this->orderScope = $orderScope;
        $this->treeBuilder = $treeBuilder;
        $this->attributeToggler = $attributeToggler;
        $this->positionUpdater = $positionUpdater;
        $this->possibleVariants = $possibleVariants;
    }

    /**
     * @inheritDoc
     */
    public function newInstance(array $data = [])
    {
        return new Node($data);
    }

    /**
     * @inheritDoc
     */
    public function findById($id, $published = false)
    {
        $query = Node::query();
        if ($published) {
            $this->treeBuilder->scopePublishedInTree(new Node(), $query);
        }

        return $query->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findByAlias($alias, $published = true)
    {
        $query = Node::where('alias', $alias);
        if ($published) {
            $this->treeBuilder->scopePublishedInTree(new Node(), $query);
        }

        return $query->first();
    }

    /**
     * @inheritDoc
     */
    public function findByType($type, $published = false)
    {
        $query = Node::where('type', $type);
        if ($published) {
            $this->treeBuilder->scopePublishedInTree(new Node(), $query);
        }

        return $query->first();
    }

    /**
     * @inheritDoc
     */
    public function allByType($type)
    {
        return Node::where('type', $type)->get();
    }


    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        $node = $this->findById($id);
        if (!is_null($node)) {
            $node->delete();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        if (empty($data['position'])) {
            $maxPosition = Node::where('parent_id', $data['parent_id'])->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }
            $data['position'] = $maxPosition + self::POSITION_STEP;
        }

        return Node::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data)
    {
        /** @var Node $node */
        $node = Node::find($id);
        if (!is_null($node)) {
            $node->update($data);
        }

        return $node;
    }

    /**
     * @inheritDoc
     */
    public function getTreePath($id)
    {
        return $this->treeBuilder->getTreePath(new Node(), $id);
    }

    /**
     * @inheritDoc
     */
    public function getTree()
    {
        return $this->treeBuilder->getTree(new Node());
    }

    /**
     * @inheritDoc
     */
    public function getCollapsedTree($id = null)
    {
        return $this->treeBuilder->getCollapsedTree(new Node(), $id);
    }

    /**
     * @inheritDoc
     */
    public function getParentVariants($id = null, $root = null)
    {
        return $this->treeBuilder->getTreeVariants(new Node(), $id, $root);
    }


    /**
     * @inheritDoc
     */
    public function getPublishedChildren($nodeId)
    {
        return $this->treeBuilder->getPublishedChildren(new Node(), $nodeId);
    }


    /**
     * @inheritDoc
     */
    private function getMenuElements($field, $published = true)
    {
        $query = Node::where($field, true);
        $this->orderScope->scopeOrdered($query);
        if ($published) {
            $this->treeBuilder->scopePublishedInTree(new Node(), $query);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function updatePositions(array $positions)
    {
        $this->positionUpdater->updatePositions(new Node(), $positions, self::POSITION_STEP);
    }

    /**
     * @inheritDoc
     */
    public function toggleAttribute($id, $attribute)
    {
        $node = $this->findById($id);
        $this->attributeToggler->toggleAttribute($node, $attribute);

        return $node;
    }

    /**
     * @inheritDoc
     */
    public function getPublishedChildrenContentModels($nodeId)
    {
        $childrenNodes = $this->getPublishedChildren($nodeId);

        $childrenContentModels = [];
        if (count($childrenNodes) > 0) {
            foreach ($childrenNodes as $chNode) {
                $childrenContentModels[] = \TypeContainer::getContentModelFor($chNode);
            }
        }

        return $childrenContentModels;
    }

    /**
     * @inheritDoc
     */
    public function findByUrl(array $url)
    {
        $node = null;
        $parentId = null;
        foreach ($url as $alias) {
            $query = Node::where('alias', $alias);
            if (is_null($parentId)) {
                $this->treeBuilder->scopeRooted($query);
            } else {
                $query->where('parent_id', $parentId);
            }
            $this->treeBuilder->scopePublishedInLvl($query);
            $node = $query->first();
            if (is_null($node)) {
                break;
            }
            $parentId = $node->id;
        }

        return $node;
    }

    /**
     * @inheritDoc
     */
    public function getDynamicPageRelativePath($nodeId)
    {
        $aliasArray = array_map(
            function ($e) {
                return $e->alias;
            },
            $this->getTreePath($nodeId)
        );

        return implode('/', $aliasArray);
    }

    /**
     * @inheritDoc
     */
    public function getSiteMapPageTree($mapPage = false)
    {
        $ignoreTypes = [
            StructureTypesServiceProvider::TYPE_ERROR_404,
        ];

        if ($mapPage) {
            $ignoreTypes[] = StructureTypesServiceProvider::TYPE_MAP_PAGE;
        }

        return $this->treeBuilder->getTree(
            new Node(),
            function ($query) use ($ignoreTypes) {
                $query->whereNotIn(
                    'type',
                    $ignoreTypes
                );
                $this->treeBuilder->scopePublishedInLvl($query);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function getMapPageTree()
    {
        $nodeTree = $this->getSiteMapPageTree(true);
        $pageTypesExists = $nodeTree->lists('type');

        if (!in_array(StructureTypesServiceProvider::TYPE_HOME_PAGE, $pageTypesExists)) {
            $node = $this->newInstance(
                [
                    'name' => HomePagesController::DEFAULT_PAGE_NAME,
                    'type' => StructureTypesServiceProvider::TYPE_HOME_PAGE,
                ]
            );
            $nodeTree->prepend($node);
        }

        return $nodeTree;
    }

    public function getAllPublishedIds()
    {
        static $allPublishedIds;
        if (!isset($allPublishedIds)) {
            $allPublishedIds = $this->treeBuilder->getPublishedIds(new Node());
        }

        return $allPublishedIds;
    }

    /**
     * @inheritDoc
     */
    public function getTopMenuElements()
    {
       return $this->getMenuElements('menu_top', true);
    }

    /**
     * @inheritDoc
     */
    public function getScrolledTopMenuElements()
    {
       return $this->getMenuElements('scrolled_menu_top', true);
    }

    /**
     * @inheritDoc
     */
    public function getBottomMenuElements()
    {
       return $this->getMenuElements('menu_bottom', true);
    }
}
