<?php
namespace App\Services\StructureTypes;

use App\Models\Node;
use App\Services\Repositories\Node\NodeRepositoryInterface;

/**
 * Class TypeContainer
 * Container to manage types of App\Models\Node.
 * @package App\Services\StructureTypes
 */
class TypeContainer
{
    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var RepositoryAssociation[]
     */
    private $repositoryAssociations = [];

    /**
     * @var Type[]
     */
    private $typeList = [];

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function addRepositoryAssociation($repositoryKey, RepositoryAssociation $repositoryAssociation)
    {
        $this->repositoryAssociations[$repositoryKey] = $repositoryAssociation;
    }


    public function addType($typeKey, Type $type)
    {
        if (!isset($this->repositoryAssociations[$type->getRepoKey()])) {
            throw new \InvalidArgumentException("Add repository with key {$type->getRepoKey()} first");
        }
        $this->typeList[$typeKey] = $type;
    }


    /**
     * @return RepositoryAssociation[]
     */
    public function getRepositoryAssociations()
    {
        return $this->repositoryAssociations;
    }


    /**
     * @return Type[]
     */
    public function getTypeList()
    {
        return $this->typeList;
    }

    /**
     * Get enabled types for list.
     *
     * @param null $nodeId
     * @return Type[]
     */
    public function getEnabledTypeList($nodeId = null)
    {
        $result = [];
        foreach ($this->typeList as $typeKey => $type) {
            if ($type->getUnique()) {
                $nodes = $this->nodeRepository->allByType($typeKey);
                $count = 0;
                foreach ($nodes as $n) {
                    if (!is_null($nodeId) && $nodeId == $n->id) {
                        continue;
                    } else {
                        $count += 1;
                    }
                }

                if ($count > 0) {
                    continue;
                }
            }

            $result[$typeKey] = $type;
        }

        return $result;
    }

    /**
     * Get name of type.
     *
     * @param $typeKey
     * @return string|null
     */
    public function getTypeName($typeKey)
    {
        if (isset($this->typeList[$typeKey])) {
            $typeName = $this->typeList[$typeKey]->getName();
        } else {
            $typeName = null;
        }

        return $typeName;
    }


    /**
     * @param Node $node
     * @return \Eloquent|null
     */
    public function getContentModelFor(Node $node)
    {
        if (!isset($this->typeList[$node->type])) {
            return null;
        } else {
            $repoKey = $this->typeList[$node->type]->getRepoKey();
            $type = $this->repositoryAssociations[$repoKey];
        }

        return $type->getNodeContentRepository()->findForNodeOrNew($node);
    }

    public function getContentUrl(Node $node)
    {
        if (isset($this->typeList[$node->type])) {
            $type = $this->typeList[$node->type];
            $repoAssociation = $this->repositoryAssociations[$type->getRepoKey()];
            $urlCreator = $repoAssociation->getUrlCreator();
            return $urlCreator($node);
        } else {
            return null;
        }
    }

    public function getClientUrl(Node $node, $absolute = true)
    {
        if (isset($this->typeList[$node->type])) {
            $clientUrlCreator = $this->typeList[$node->type]->getClientUrlCreator();
            $clientUrl = $clientUrlCreator($node);

            if (!$absolute) {
                $clientUrl = '/' . ltrim(str_replace(\Request::getSchemeAndHttpHost(), '', $clientUrl), '/');
            }

            return $clientUrl;
        } else {
            return null;
        }
    }

    public function getDynamicPageRelativePath($nodeId)
    {
        return $this->nodeRepository->getDynamicPageRelativePath($nodeId);
    }
}
