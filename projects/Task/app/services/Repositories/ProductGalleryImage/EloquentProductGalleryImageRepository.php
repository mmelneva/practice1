<?php namespace App\Services\Repositories\ProductGalleryImage;

use App\Models\CatalogProduct;
use App\Models\ProductGalleryImage;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;

/**
 * Class EloquentProductGalleryImageRepository
 * @package  App\Services\Repositories\ProductGalleryImage
 */
class EloquentProductGalleryImageRepository implements ProductGalleryImageRepositoryInterface
{
    const POSITION_STEP = 10;

    /**
     * @var ProductGalleryImage
     */
    protected $modelInstance;

    /**
     * @var EloquentAttributeToggler
     */
    protected $attributeToggler;

    /**
     * @param EloquentAttributeToggler $attributeToggler
     */
    public function __construct(EloquentAttributeToggler $attributeToggler, ProductGalleryImage $productGalleryImage)
    {
        $this->modelInstance = $productGalleryImage;
        $this->attributeToggler = $attributeToggler;
    }

    /**
     * @inheritDoc
     */
    public function findById($id)
    {
        return $this->modelInstance->find($id);
    }

    public function newInstance(array $attributes = [], $exists = false)
    {
        return $this->modelInstance->newInstance($attributes, $exists);
    }

    public function createOrUpdate($productId, $imageId, array $imageData = [])
    {
        if ((!(isset($imageData['position']) && $imageData['position'] === '0')) && empty($imageData['position'])) {
            $maxPosition = $this->modelInstance->where('catalog_product_id', $productId)->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }
            $imageData['position'] = $maxPosition + self::POSITION_STEP;
        }

        $existingImage = $this->modelInstance->where('catalog_product_id', $productId)
            ->where('id', $imageId)->first();

        if (is_null($existingImage)) {
            /** @var ProductGalleryImage $existingImage */
            $existingImage = $this->modelInstance->newInstance();
            $existingImage->catalog_product_id = $productId;
        }


        $existingImage->fill($imageData);
        $existingImage->save();

        return $existingImage;
    }

    public function deleteNotIn($productId, array $imageIdList)
    {
        $query = $this->modelInstance->where('catalog_product_id', $productId);
        if (count($imageIdList) > 0) {
            $query->whereNotIn('id', $imageIdList);
        }
        $modelInstances = $query->get();
        /** @var ProductGalleryImage $modelInstance */
        foreach ($modelInstances as $modelInstance) {
            $modelInstance->delete();
        }
    }

    /**
     * @inheritDoc
     */
    public function toggleAttribute($id, $attribute)
    {
        $model = $this->findById($id);
        if (!is_null($model)) {
            $this->attributeToggler->toggleAttribute($model, $attribute);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    private function scopePublished($query)
    {
        return $query->where('publish', true);
    }

    /**
     * @inheritDoc
     */
    private function scopeOrdered($query)
    {
        return $query->orderBy('product_gallery_images.position');
    }

    /**
     * @inheritDoc
     */
    public function allForProduct(CatalogProduct $product, $published = false)
    {
        $query = $product->galleryImages();
        $this->scopeOrdered($query);
        if ($published) {
            $this->scopePublished($query);
        }

        return $query->get();
    }
}
