<?php namespace App\Services\Validation\ProductTypePageAssociation;

use App\Services\Validation\AbstractLaravelValidator;

class ProductTypePageAssociationLaravelValidator extends AbstractLaravelValidator
{
    protected function getRules()
    {
        return [
            'position' => 'integer',
        ];
    }
}
