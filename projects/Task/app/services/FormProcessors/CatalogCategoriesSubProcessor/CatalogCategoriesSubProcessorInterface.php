<?php namespace App\Services\FormProcessors\CatalogCategoriesSubProcessor;

use App\Models\CatalogCategory;

/**
 * Interface SubProcessorInterface
 * @package App\Services\FormProcessors
 */
interface CatalogCategoriesSubProcessorInterface
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
     * @param CatalogCategory $category
     * @param $formData
     * @return mixed
     */
    public function process(CatalogCategory $category, $formData);
}
