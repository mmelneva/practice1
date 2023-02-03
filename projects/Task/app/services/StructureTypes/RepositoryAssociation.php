<?php namespace App\Services\StructureTypes;

use App\Services\Repositories\Node\NodeContentRepositoryInterface;

class RepositoryAssociation
{
    private $nodeContentRepository;
    private $urlCreator;

    public function __construct(NodeContentRepositoryInterface $nodeContentRepository, callable $urlCreator)
    {
        $this->nodeContentRepository = $nodeContentRepository;
        $this->urlCreator = $urlCreator;
    }

    /**
     * @return callable
     */
    public function getUrlCreator()
    {
        return $this->urlCreator;
    }

    /**
     * @return NodeContentRepositoryInterface
     */
    public function getNodeContentRepository()
    {
        return $this->nodeContentRepository;
    }
}
