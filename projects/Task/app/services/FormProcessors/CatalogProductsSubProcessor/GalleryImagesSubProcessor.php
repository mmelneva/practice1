<?php namespace App\Services\FormProcessors\CatalogProductsSubProcessor;

use App\Models\CatalogProduct;
use App\Services\Repositories\ProductGalleryImage\ProductGalleryImageRepositoryInterface;

/**
 * Class GalleryImagesSubProcessor
 * @package  App\Services\FormProcessors\CatalogProductsSubProcessor
 */
class GalleryImagesSubProcessor implements CatalogProductSubProcessorInterface
{
    public function __construct(ProductGalleryImageRepositoryInterface $galleryImageRepository)
    {
        $this->galleryImageRepository = $galleryImageRepository;
    }

    /**
     * @inheritdoc
     */
    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    /**
     * @inheritdoc
     */
    public function process(CatalogProduct $catalogProduct, $formData)
    {
        $galleryImagesData = array_get($formData, 'images', []);

        $galleryImageIdList = [];

        foreach ($galleryImagesData as $key => $imageData) {
            if (empty($imageData['delete'])) {
                $allowedValue = $this->galleryImageRepository->createOrUpdate(
                    $catalogProduct->id,
                    $key,
                    $imageData
                );
                $galleryImageIdList[] = $allowedValue->id;
            }
        }

        $this->galleryImageRepository->deleteNotIn($catalogProduct->id, $galleryImageIdList);
    }
}
