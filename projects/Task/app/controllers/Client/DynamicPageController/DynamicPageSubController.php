<?php namespace App\Controllers\Client\DynamicPageController;

use App\Models\Node;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class DynamicPageSubController
{
    protected $nodeRepository;
    private $_exceptions = [
        'shkafy-kupe/vstroennye' => '/cat/shkafy-kupe',
        'shkafy-kupe/korpusnye' => '/cat/shkafy-kupe',
        'raspashnye-shkafy/vstraivaemye' => '/cat/raspashnye-shkafy',
        'raspashnye-shkafy/korpusnyj' => '/cat/raspashnye-shkafy',
    ];

    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    protected function getBreadcrumbs($node)
    {
        $breadcrumbs = \Breadcrumbs::initFromCollection(
            $this->nodeRepository->getTreePath($node->id),
            function (Node $node) {
                return [$node->name, \UrlBuilder::getUrl($node)];
            }
        );

        $currentPath = \Request::path();
        if (in_array($currentPath, array_keys($this->_exceptions))) {
            $firstCrumb = array_get($breadcrumbs->getBreadcrumbs(), 0);
            $breadcrumbs->change(0, $firstCrumb['name'], array_get($this->_exceptions, $currentPath));
        }

        return $breadcrumbs;
    }
}
