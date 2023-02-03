<?php namespace App\Services\Composers;

use App\Services\Repositories\Node\NodeRepositoryInterface;

class ClientRegionsLinkComposer
{
    private $nodeRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function compose($view)
    {
        $nodeId = 162;
        $children = $this->nodeRepository->getPublishedChildrenContentModels($nodeId);
        $view->with('children', $children);
    }
}
