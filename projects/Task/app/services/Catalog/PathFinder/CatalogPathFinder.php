<?php namespace App\Services\Catalog\PathFinder;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;

class CatalogPathFinder
{
    private $catalogPathCache = [];

    public function getProductRoot(CatalogProduct $catalogProduct)
    {
        return $this->getCategoryRoot($catalogProduct->category);
    }

    public function getProductPath(CatalogProduct $catalogProduct)
    {
        return $this->getCategoryPath($catalogProduct->category);
    }

    public function getCategoryRoot(CatalogCategory $catalogCategory)
    {
        return array_get($this->getCategoryPath($catalogCategory), 0);
    }

    public function getCategoryPath(CatalogCategory $catalogCategory)
    {
        $currentCategory = $catalogCategory;

        if (!isset($this->catalogPathCache[$currentCategory->id])) {
            $path = [$catalogCategory];
            if (!is_null($catalogCategory->parent_id) && !is_null($catalogCategory->parent)) {
                $path = array_merge($this->getCategoryPath($catalogCategory->parent), $path);
            }

            $this->catalogPathCache[$currentCategory->id] = $path;
        }

        return $this->catalogPathCache[$currentCategory->id];
    }
}
