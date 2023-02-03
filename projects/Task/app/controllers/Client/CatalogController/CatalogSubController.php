<?php namespace App\Controllers\Client\CatalogController;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;

/**
 * Class CatalogSubController
 * Abstract controller for catalog sub controllers.
 * @package App\Controllers\Client\CatalogController
 */
abstract class CatalogSubController
{
    protected $nodeRepository;
    protected $catalogCategoryRepository;

    public function __construct(NodeRepositoryInterface $nodeRepository, CatalogCategoryRepositoryInterface $catalogCategoryRepository)
    {
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    /**
     * Get breadcrumbs for catalog item - category or product.
     *
     * @param $catalogItem
     * @param bool|true $isLink
     * @return \App\Services\Breadcrumbs\Container
     */

    protected function getBreadcrumbs($catalogItem, $isLink = true)
    {
        if ($catalogItem instanceof CatalogProduct) {
            $catalogPath = \CatalogPathFinder::getProductPath($catalogItem);
            $catalogPath = $this->getDataPath($catalogPath) ;
            $catalogPath[] = $catalogItem;
        } elseif ($catalogItem instanceof CatalogCategory) {
            $catalogPath = \CatalogPathFinder::getCategoryPath($catalogItem);
        } else {
            throw new \InvalidArgumentException;
        }

        $breadcrumbs = \Breadcrumbs::init();

        for ($i = 0; $i < count($catalogPath); $i += 1) {
            $c = $catalogPath[$i];

            if ($isLink) {
                $breadcrumbs->add($c->name, \UrlBuilder::getUrl($c));
            } else {
                $breadcrumbs->add($c->name);
            }
        }
        return $breadcrumbs;
    }

    protected function getDataPath($catalogPath)
    {
        $backLink = \Request::server('HTTP_REFERER');
        $pathArray = explode('/', $backLink);
        $lastAlias = array_get($pathArray, count($pathArray) - 1);
        if (isset($backLink) && preg_match('/^tovar-(\d+)$/', $lastAlias)) {
            $backLink = \Session::get('backLink');
        } else {
            \Session::set('backLink', $backLink);
        }
        $alias = substr($backLink, strrpos($backLink, "/") + 1);

        $dataNode = $this->nodeRepository->findByAlias($alias);

        if (stristr($backLink, '/cat/')) {
            $dataCategory = $this->catalogCategoryRepository->findByAlias($alias);
            if (!is_null($dataCategory)) {
                $dataCategory = $this->catalogCategoryRepository->getTreePath($dataCategory['id']);
                $catalogPath = $dataCategory;
            }
        } else {
            if (!is_null($dataNode)) {
                $dataNodes = $this->nodeRepository->getTreePath($dataNode['id']);
                $catalogPath = $dataNodes;
            }
        }
        return $catalogPath;
    }

}

