<?php namespace App\Services\Validation\ProductTypePage;

use App\Models\ProductTypePage;
use App\Services\Validation\AbstractLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;
use App\Services\Validation\ProductTypePageAssociation\ProductTypePageAssociationLaravelValidator;

class ProductTypePageLaravelValidator extends AbstractLaravelValidator
{
    public function __construct(
        ValidatorFactory $validatorFactory,
        ProductTypePageAssociationLaravelValidator $productTypePageAssociationLaravelValidator
    ) {
        parent::__construct($validatorFactory);

        $this->productTypePageAssociationLaravelValidator = $productTypePageAssociationLaravelValidator;
    }

    protected function getRules()
    {
        return [];
    }

    public function passes()
    {
        return parent::passes()
        && $this->passesProductTypePageAssociation();
    }

    public function passesProductTypePageAssociation()
    {
        $productListWay = array_get($this->data, 'product_list_way');
        if ($productListWay == ProductTypePage::WAY_MANUAL) {
            $type = 'manual';
            $productTypePageAssociationData = array_get($this->data, 'associated_products.manual', []);
        } elseif ($productListWay == ProductTypePage::WAY_FILTERED) {
            $productTypePageAssociationData = array_get($this->data, 'associated_products.filtered', []);
            $type = 'filtered';
        } else {
            $productTypePageAssociationData = [];
            $type = '';
        }

        if (is_array($productTypePageAssociationData)) {
            $allPasses = true;
            foreach ($productTypePageAssociationData as $productId => $associationData) {
                $passes = $this->productTypePageAssociationLaravelValidator->with($associationData)->passes();

                if (!$passes) {
                    $errors = $this->productTypePageAssociationLaravelValidator->errors();
                    foreach ($errors as $errorKey => $errorMessage) {
                        $this->errors["associated_products.{$type}.{$productId}.{$errorKey}"] = $errorMessage;
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
