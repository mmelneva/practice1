<?php namespace App\Services\FormProcessors\ReviewsSubProcessor;

use App\Models\Reviews;

/**
 * Class AssociatedCategoriesSubProcessor
 * @package App\Services\FormProcessors\ReviewsSubProcessor
 */
class AssociatedProductTypePagesSubProcessor implements ReviewsSubProcessorInterface
{
    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    public function process(Reviews $review, $formData)
    {
        $idList = array_get($formData, 'associated_product_type_pages', []);
        unset($idList[0]);
        $idList = array_keys($idList);

        $changes = $review->associatedProductTypePages()->sync($idList);
        if (!empty($changes['attached']) || !empty($changes['detached']) || $changes['updated']) {
            $review->touch();
        }

    }
}
