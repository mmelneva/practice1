<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Controllers\Client\DynamicPageController\ProductTypePageSubController;
use App\Controllers\Client\DynamicPageController\TextPageSubController;
use App\Models\ProductTypePage;
use App\Models\TextPage;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class DynamicPageController extends BaseController
{
    private $nodeRepository;
    private $textPageSubController;
    private $productTypePageSubController;

    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        TextPageSubController $textPageSubController,
        ProductTypePageSubController $productTypePageSubController
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->textPageSubController = $textPageSubController;
        $this->productTypePageSubController = $productTypePageSubController;
    }

    public function getShow($alias)
    {
        list($url, $page) = $this->parseDynamicQuery($alias);
        $node = $this->nodeRepository->findByUrl($url);

        if (is_null($node)) {
            \App::abort(404, 'Node not found by alias');
        }

        $contentModel = \TypeContainer::getContentModelFor($node);
        if ($contentModel instanceof TextPage) {
            if (!is_null($page)) {
                \App::abort(404, 'Pagination is not allowed here');
            }

            return $this->textPageSubController->getTextPageResponse($node, $contentModel);
        } elseif ($contentModel instanceof ProductTypePage) {
            if ($page == 1) {
                \App::abort(404, 'Page 1 is not allowed in url');
            }
            
            return $this->productTypePageSubController->getProductTypePageResponse(
                $node,
                $contentModel,
                is_null($page) ? 1 : $page
            );
        } else {
            \App::abort(404, 'Unknown node type');

            return null;
        }
    }

    private function parseDynamicQuery($dynamicQuery)
    {
        $queryArray = explode('/', $dynamicQuery);

        $lastIndex = count($queryArray) - 1;
        if (preg_match('/^page-([1-9]\d*)$/', $queryArray[$lastIndex], $matches)) {
            $page = $matches[1];
            unset($queryArray[$lastIndex]);
        } else {
            $page = null;
        }

        return [$queryArray, $page];
    }
}
