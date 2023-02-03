<?php namespace App\Services\FormProcessors\AttributesSubProcessor;

use App\Models\Attribute;

/**
 * Class AttributeSubProcessorInterface
 * @package  App\Services\FormProcessors\AttributesSubProcessor
 */
interface AttributeSubProcessorInterface
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
     * @param Attribute $attribute
     * @param $formData
     * @return mixed
     */
    public function process(Attribute $attribute, $formData);
}
