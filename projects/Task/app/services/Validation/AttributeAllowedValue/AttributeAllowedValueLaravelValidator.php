<?php namespace App\Services\Validation\AttributeAllowedValue;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class AttributeAllowedValueLaravelValidator
 * @package  App\Services\Validation\AttributeAllowedValue
 */
class AttributeAllowedValueLaravelValidator extends AbstractLaravelValidator
{
    protected function getRules()
    {
        return [
            'value' => ['required'],
        ];
    }
}
