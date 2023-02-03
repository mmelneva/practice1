<?php namespace App\Services\Validation\Reviews;

use Illuminate\Validation\Validator;

/**
 * Class AdminReviewsLaravelValidator
 * @package  App\Services\Validation\Reviews
 */
class AdminReviewsLaravelValidator extends ReviewsLaravelValidator
{
    /**
     * {@inheritDoc}
     */
    protected function configValidator(Validator $validator)
    {
        $validator->sometimes(
            'answer',
            'required',
            function ($input) {
                return !empty($input->optional) && $input->optional == 'send_answer';
            }
        );

        $validator->sometimes(
            'email',
            'required',
            function ($input) {
                return !empty($input->optional) && $input->optional == 'send_answer';
            }
        );
    }
}
