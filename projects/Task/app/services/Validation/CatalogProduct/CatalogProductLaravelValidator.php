<?php namespace App\Services\Validation\CatalogProduct;

use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Validation\AbstractLaravelValidator;
use App\Services\Validation\CatalogProductAttribute\CatalogProductAttributeLaravelValidator;
use App\Services\Validation\ProductGalleryImage\ProductGalleryImageLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

class CatalogProductLaravelValidator extends AbstractLaravelValidator
{

    public function __construct(
        ValidatorFactory $validatorFactory,
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogProductAttributeLaravelValidator $catalogProductAttributeLaravelValidator,
        ProductGalleryImageLaravelValidator $galleryImageLaravelValidator
    ) {
        parent::__construct($validatorFactory);

        $this->catalogProductRepository = $catalogProductRepository;
        $this->catalogProductAttributeLaravelValidator = $catalogProductAttributeLaravelValidator;
        $this->galleryImageLaravelValidator = $galleryImageLaravelValidator;
    }

    protected function getRules()
    {
        return [
            'category_id' => ['required', 'exists:catalog_categories,id'],
            'associated_categories' => 'exists:catalog_categories,id',
            'name' => 'required',
            'position' => 'integer',
            'image_file' => 'local_or_remote_image:jpeg,jpg,png,gif',
            'preview_image_file' => 'local_or_remote_image:jpeg,jpg,png,gif',
            'built_in' => ['in:' . implode(',', array_flip($this->catalogProductRepository->getBuiltInVariants()))],
        ];
    }

    public function passes()
    {
        return parent::passes()
        && $this->passesAdditionalAttributes()
        && $this->passesGalleryImages();
    }

    public function passesAdditionalAttributes()
    {
        $additionalAttributes = array_get($this->data, 'additional_attributes', []);

        if (is_array($additionalAttributes)) {
            $allPasses = true;
            foreach ($additionalAttributes as $additionalAttributeKey => $additionalAttributesData) {
                $passes = $this->catalogProductAttributeLaravelValidator->with($additionalAttributesData)->passes();

                if (!$passes) {
                    $errors = $this->catalogProductAttributeLaravelValidator->errors();
                    foreach ($errors as $errorKey => $errorMessage) {
                        $this->errors["additional_attributes.{$additionalAttributeKey}.{$errorKey}"] = $errorMessage;
                    }
                }
                $allPasses = $allPasses && $passes;
            }
        } else {
            $allPasses = false;
        }

        return $allPasses;
    }

    public function passesGalleryImages()
    {
        $galleryImages = array_get($this->data, 'images', []);

        if (is_array($galleryImages)) {
            $allPasses = true;
            foreach ($galleryImages as $imageKey => $imageData) {

                // If image is not new.
                if (!preg_match('/^new_/', $imageKey)) {
                    if (empty($imageData['image_file'])) {
                        unset($imageData['image_file']);
                    }
                }

                $passes = $this->galleryImageLaravelValidator->with($imageData)->passes();

                if (!$passes) {
                    $errors = $this->galleryImageLaravelValidator->errors();
                    foreach ($errors as $errorKey => $errorMessage) {
                        $this->errors["images.{$imageKey}.{$errorKey}"] = $errorMessage;
                    }
                }
                $allPasses = $allPasses && $passes;
            }
        } else {
            $allPasses = false;
        }

        return $allPasses;
    }
}
