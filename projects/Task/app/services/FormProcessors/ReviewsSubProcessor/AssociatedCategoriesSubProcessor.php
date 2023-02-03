<?php namespace App\Services\FormProcessors\ReviewsSubProcessor;

use App\Models\Reviews;

/**
 * Class AssociatedCategoriesSubProcessor
 * @package App\Services\FormProcessors\ReviewsSubProcessor
 */
class AssociatedCategoriesSubProcessor implements ReviewsSubProcessorInterface
{
    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    public function process(Reviews $review, $formData)
    {

        $idList = array_get($formData, 'associated_categories', []);
        $idList = array_keys($idList);

        $changes = $review->associatedCategories()->sync($idList);
        if (!empty($changes['attached']) || !empty($changes['detached']) || $changes['updated']) {
            $review->touch();
        }
    }
}
