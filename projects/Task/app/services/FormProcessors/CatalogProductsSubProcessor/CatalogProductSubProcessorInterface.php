<?php namespace App\Services\FormProcessors\CatalogProductsSubProcessor;

use App\Models\CatalogProduct;

/**
 * Interface SubProcessorInterface
 * @package App\Services\FormProcessors
 */
interface CatalogProductSubProcessorInterface
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
     * @param CatalogProduct $catalogProduct
     * @param $formData
     * @return mixed
     */
    public function process(CatalogProduct $catalogProduct, $formData);
}
