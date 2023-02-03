<?php namespace App\Services\Composers;

use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class ClientTopMenuComposer
{
    private $nodeRepository;
    private $catalogCategoryRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository, CatalogCategoryRepositoryInterface $catalogCategoryRepository)
    {
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    public function compose($view)
    {
        $view->with('topMenu', $this->nodeRepository->getTopMenuElements());
    }
}
