<?php namespace App\Services\FormProcessors;

use App\Models\CallbackStatusConstants;
use App\Services\Repositories\Callback\CallbackRepositoryInterface;
use App\Services\Validation\Callback\CallbackLaravelValidator;

/**
 * Class CallbackFormProcessor
 * @package  App\Services\FormProcessors
 */
class CallbackFormProcessor extends CreateUpdateFormProcessor
{
    public function __construct(
        CallbackLaravelValidator $validator,
        CallbackRepositoryInterface $repository
    ) {
        parent::__construct($validator, $repository);
    }

    protected function prepareInputData(array $data)
    {
        // Prepare phone
        if (isset($data['phone'])) {
            $data['phone'] = str_replace('-', '', $data['phone']);
        }

        if (!isset($data['callback_status'])) {
            $data['callback_status'] = CallbackStatusConstants::NOVEL;
        }

        return $data;
    }
}
