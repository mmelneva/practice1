<?php namespace App\Services\FormProcessors\HomePagesSubProcessor;

use App\Models\HomePage;

/**
 * Class HomePageSubProcessorInterface
 * @package  App\Services\FormProcessors\HomePagesSubProcessor
 */
interface HomePageSubProcessorInterface
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
     * @param HomePage $homePage
     * @param $formData
     * @return mixed
     */
    public function process(HomePage $homePage, $formData);
}
