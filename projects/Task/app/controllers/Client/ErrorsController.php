<?php
namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Models\Node;

class ErrorsController extends BaseController
{
    const DEFAULT_PAGE_NAME = 'Страница не найдена';

    private $nodeRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function getError404()
    {
        $node = $this->nodeRepository->findByType(StructureTypesServiceProvider::TYPE_ERROR_404, true);
        $breadcrumbs = \Breadcrumbs::init();
        if (!is_null($node)) {
            $errorPage = \TypeContainer::getContentModelFor($node);
            $metaData = \MetaHelper::metaForObject($errorPage, $node->name);
            $breadcrumbs->add($node->name);
        } else {
            $errorPage = null;
            $metaData = \MetaHelper::metaForName(self::DEFAULT_PAGE_NAME);
            $breadcrumbs->add(self::DEFAULT_PAGE_NAME);
        }

        $viewData = array_merge(['errorPage' => $errorPage, 'breadcrumbs' => $breadcrumbs, 'hideRegionsInPage' => $node->hide_regions_in_page], $metaData);

        return \Response::view('client.errors.404', $viewData, 404);
    }
}
