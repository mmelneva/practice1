<?php namespace App\Services\Catalog\UrlBuilder;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;

/**
 * Class CatalogUrlBuilder
 * @package App\Services\Catalog\UrlBuilder
 */
class CatalogUrlBuilder
{
    /**
     * @var string catalog route name.
     */
    private $routeName;

    /**
     * @param string $routeName - catalog route name.
     */
    public function __construct($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * Get category url.
     *
     * @param CatalogCategory $category
     * @return string
     */
    public function getCategoryUrl(CatalogCategory $category)
    {
        $path = \CatalogPathFinder::getCategoryPath($category);
        $aliasPath = $this->getAliasList($path);

        return $this->buildUrl($aliasPath);
    }

    /**
     * Get product url.
     *
     * @param CatalogProduct $product
     * @return string
     */
    public function getProductUrl(CatalogProduct $product)
    {
        $path = \CatalogPathFinder::getProductPath($product);

        $aliasPath = $this->getAliasList($path);
        $aliasPath[] = 'tovar-' . $product->id;

        return $this->buildUrl($aliasPath);
    }


    /**
     * Get catalog url.
     *
     * @param $catalogItem
     * @return string
     */
    public function getCatalogUrl($catalogItem)
    {
        if ($catalogItem instanceof CatalogCategory) {
            return $this->getCategoryUrl($catalogItem);
        } elseif ($catalogItem instanceof CatalogProduct) {
            return $this->getProductUrl($catalogItem);
        } else {
            throw new \InvalidArgumentException('Catalog item should be category or product model');
        }
    }

    /**
     * Get list of aliases.
     *
     * @param array $categories
     * @return array
     */
    private function getAliasList(array $categories)
    {
        $aliasPath = [];
        foreach ($categories as $c) {
            $aliasPath[] = $c->alias;
        }

        return $aliasPath;
    }

    /**
     * Build url.
     *
     * @param array $aliasPath
     * @return string
     */
    private function buildUrl(array $aliasPath)
    {
        return route($this->routeName, implode('/', $aliasPath));
    }
}
