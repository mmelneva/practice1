<?php namespace App\Services\Composers;

use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class ClientMeasurementLinkComposer
{
    private $nodeRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function compose($view)
    {
        $node = $this->nodeRepository->findByType(StructureTypesServiceProvider::TYPE_MEASUREMENT_PAGE, true);
        $view->with('measurementNode', $node);
    }
}
