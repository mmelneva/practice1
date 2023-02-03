<?php namespace App\Services\Repositories\ProductGalleryImage;

use App\Models\CatalogProduct;
use App\Services\Repositories\ToggleableRepositoryInterface;

/**
 * Interface ProductGalleryImageRepositoryInterface
 * @package  App\Services\Repositories\ProductGalleryImage
 */
interface ProductGalleryImageRepositoryInterface extends ToggleableRepositoryInterface
{
    public function findById($id);

    public function newInstance(array $attributes = [], $exists = false);

    public function createOrUpdate($productId, $imageId, array $imageData = []);

    public function deleteNotIn($productId, array $imageIdList);

    /**
     * Get all gallery images for this product.
     *
     * @param CatalogProduct $product
     * @param bool|false $published
     * @return mixed
     */
    public function allForProduct(CatalogProduct $product, $published = false);
}
