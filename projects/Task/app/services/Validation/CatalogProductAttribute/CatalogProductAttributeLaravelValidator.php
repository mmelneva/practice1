<?php namespace App\Services\Validation\CatalogProductAttribute;

use App\Services\Validation\AbstractLaravelValidator;
use Illuminate\Validation\Validator;

/**
 * Class CatalogProductAttributeLaravelValidator
 * @package  App\Services\Validation\CatalogProductAttribute
 */
class CatalogProductAttributeLaravelValidator extends AbstractLaravelValidator
{
    protected function getRules()
    {
        $rules = [];

        if (isset($this->data['allowed_value_id_list'])) {
            $rules['allowed_value_id_list'] = ['required', 'multi_exists:attribute_allowed_values,id'];
        } elseif (!empty($this->data['allowed_value_id'])) {
            $rules['allowed_value_id'] = ['required', 'exists:attribute_allowed_values,id'];
        } elseif (isset($this->data['value'])) {
            if(isset($this->data['is_number_value'])) {
                $rules['value'] = 'numeric';
            }
            // nothing
        }

        return $rules;
    }

    protected function getMessages()
    {
        return [
            'value.numeric' => 'Поле должно быть числом.',
        ];
    }
}
