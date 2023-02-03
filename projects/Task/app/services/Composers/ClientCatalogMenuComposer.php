<?php namespace App\Services\Composers;

use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;

class ClientCatalogMenuComposer
{
    private $catalogCategoryRepository;

    public function __construct(CatalogCategoryRepositoryInterface $catalogCategoryRepository)
    {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    public function compose($view)
    {
        $categories = $this->catalogCategoryRepository->menuElements();

        $view->with('catalogMenu', $categories);
    }
}
