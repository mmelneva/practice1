<?php namespace App\Services\FormProcessors\ReviewsSubProcessor;

use App\Models\Reviews;

/**
 * Interface SubProcessorInterface
 * @package App\Services\FormProcessors
 */
interface ReviewsSubProcessorInterface
{
    /**
     * Prepare input data.
     *
     * @param $inputData
     * @return mixed
     */
    public function prepareInputData($inputData);

    /**
     * Process form data.
     *
     * @param Reviews $review
     * @param $formData
     * @return mixed
     */
    public function process(Reviews $review, $formData);
}
