<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Node;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class MeasurementPagesController extends BaseController
{
    private $nodeRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function getIndex()
    {
        $node = $this->nodeRepository->findByType(StructureTypesServiceProvider::TYPE_MEASUREMENT_PAGE, true);
        if (is_null($node)) {
            \App::abort(404, 'Page not found');
        }

        $measurementPage = \TypeContainer::getContentModelFor($node);
        $metaData = \MetaHelper::metaForObject($measurementPage, $node->name);
        $hideRegionsInPage = $node->hide_regions_in_page;
        $breadcrumbs = \Breadcrumbs::initFromCollection(
            $this->nodeRepository->getTreePath($node->id),
            function (Node $node) {
                return [$node->name, \UrlBuilder::getUrl($node)];
            }
        );

        return \View::make('client.measurement_pages.page')
            ->with('measurementPage', $measurementPage)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('hideRegionsInPage', $hideRegionsInPage)
            ->with($metaData);
    }
}
