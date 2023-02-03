<?php namespace App\Services\Validation\Reviews;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class ReviewsLaravelValidator
 * @package  App\Services\Validation\Reviews
 */
class ReviewsLaravelValidator extends AbstractLaravelValidator
{
    protected function getRules()
    {
        return [
            'name' => 'required',
            'email' => 'email',
            'comment' => 'required',
            'product_id' => 'exists:catalog_products,id',
        ];
    }

    protected function getMessages()
    {
        return [
            'name.required' =>
                'Поле ' . trans('validation.attributes.full_name') . ' обязательно для заполнения.',
        ];
    }
}
