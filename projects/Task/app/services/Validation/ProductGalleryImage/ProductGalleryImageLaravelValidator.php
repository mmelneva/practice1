<?php namespace App\Services\Validation\ProductGalleryImage;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class ProductGalleryImageLaravelValidator
 * @package  App\Services\Validation\ProductGalleryImage
 */
class ProductGalleryImageLaravelValidator extends AbstractLaravelValidator
{
    public function getRules()
    {
        return [
            'image_file' => 'sometimes|required|mimes:jpeg,png,gif',
        ];
    }
}
