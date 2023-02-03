<?php namespace App\Services\DataProviders\CatalogProduct;

use App\Models\CatalogProduct;
use App\Services\Repositories\ProductGalleryImage\ProductGalleryImageRepositoryInterface;

/**
 * Class ProductGalleryImagesDataProvider
 * @package  App\Services\DataProviders\CatalogProduct
 */
class ProductGalleryImagesDataProvider
{
    private $galleryImageRepository;

    public function __construct(ProductGalleryImageRepositoryInterface $galleryImageRepository) {
        $this->galleryImageRepository = $galleryImageRepository;
    }

    public function getGalleryImagesFormData(CatalogProduct $product, array $oldData = [])
    {
        $images = [];
        foreach (array_get($oldData, 'images', []) as $key => $featureData) {
            $image = $this->galleryImageRepository->findById($key);
            if (is_null($image)) {
                $image = $this->galleryImageRepository->newInstance($featureData);
            } else {
                $image->fill($featureData);
            }

            $image->id = $key;
            $images[$key] = $image;
        }

        array_set($oldData, 'images', $images);

        if (count($images) == 0) {
            $images = $this->galleryImageRepository->allForProduct($product);
        }

        $opened_images = [];
        foreach ($images as $image) {
            if (\Input::old("images.{$image->id}.opened") == 1) {
                $opened_images[] = $image->id;
            }
        }

        return compact('images', 'opened_images');
    }
}
