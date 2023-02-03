<?php namespace App\Services\Validation\ValidationRules;

use App\Models\CatalogProduct;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use Illuminate\Validation\Factory as ValidatorFactory;

/**
 * Class CatalogProductRule
 * @package  App\Services\Validation\ValidationRules
 */
class CatalogProductRule
{
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        ValidatorFactory $validatorFactory
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Check if value is id of published product.
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateProductPublish($attribute, $value)
    {
        /** @var CatalogProduct $catalogProduct */
        $catalogProduct = $this->catalogProductRepository->findById($value);

        if (!is_null($catalogProduct)) {
            return $catalogProduct->publish;
        }

        return false;
    }

    /**
     * Check if value is valid price of product.
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateProductPrice($attribute, $value)
    {
        $catalogProduct = $this->catalogProductRepository->findById($value);

        if (!is_null($catalogProduct)) {
            return $catalogProduct->price > 0;
        }

        return false;
    }
}
