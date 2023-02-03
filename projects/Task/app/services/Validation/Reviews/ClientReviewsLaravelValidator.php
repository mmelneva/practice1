<?php namespace App\Services\Validation\Reviews;

/**
 * Class ClientReviewsLaravelValidator
 * @package  App\Services\Validation\Reviews
 */
class ClientReviewsLaravelValidator extends ReviewsLaravelValidator
{
    protected function getRules()
    {
        $rules = parent::getRules();
        $rules['email'] = ['required', 'email'];

        return $rules;
    }
}
