<?php
namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class SiteMapController extends BaseController
{
    const DEFAULT_PAGE_NAME = 'Карта сайта';

    private $nodeRepository;
    private $catalogCategoryRepository;

    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    public function getSiteMapPage()
    {
        $node = $this->nodeRepository->findByType(StructureTypesServiceProvider::TYPE_MAP_PAGE, true);
        $breadcrumbs = \Breadcrumbs::init();
        if (!is_null($node)) {
            $mapPage = \TypeContainer::getContentModelFor($node);
            $metaData = \MetaHelper::metaForObject($mapPage, $node->name);
            $breadcrumbs->add($node->name);
            $hideRegionsInPage = $node->hide_regions_in_page;
        } else {
            $mapPage = null;
            $metaData = \MetaHelper::metaForName(self::DEFAULT_PAGE_NAME);
            $breadcrumbs->add(self::DEFAULT_PAGE_NAME);
            $hideRegionsInPage = false;
        }

        $viewData = array_merge(['mapPage' => $mapPage, 'breadcrumbs' => $breadcrumbs, 'hideRegionsInPage' => $hideRegionsInPage], $metaData);

        $nodeTree = $this->nodeRepository->getMapPageTree();

        $categoryTree = $this->catalogCategoryRepository->getSiteMapTree();

        return \View::make('client.site_map.site_map')->with($viewData)
            ->with(compact('nodeTree', 'categoryTree'));
    }
}
