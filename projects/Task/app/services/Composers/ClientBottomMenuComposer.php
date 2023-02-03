<?php namespace App\Services\Composers;

use App\Services\Repositories\Node\NodeRepositoryInterface;

class ClientBottomMenuComposer
{
    private $nodeRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function compose($view)
    {
        $view->with('bottomMenu', $this->nodeRepository->getBottomMenuElements());
    }
}
