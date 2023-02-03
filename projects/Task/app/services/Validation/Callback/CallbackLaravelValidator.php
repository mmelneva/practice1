<?php namespace App\Services\Validation\Callback;

use App\Models\CallbackTypeConstants;
use App\Services\Repositories\Callback\CallbackRepositoryInterface;
use App\Services\Validation\AbstractLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

/**
 * Class CallbackLaravelValidator
 * @package  App\Services\Validation\Callback
 */
class CallbackLaravelValidator extends AbstractLaravelValidator
{
    public function __construct(
        ValidatorFactory $validatorFactory,
        CallbackRepositoryInterface $callbackRepository
    ) {
        parent::__construct($validatorFactory);

        $this->callbackRepository = $callbackRepository;
    }

    protected function getRules()
    {
        return [
            'phone' => ['required', 'phone'],
            'callback_status' => ['in:' . implode(',', array_flip($this->callbackRepository->getStatusVariants()))],
            'type' => ['in:' . implode(',', array_flip($this->callbackRepository->getTypeVariants()))],
        ];
    }

    /**
     * Config existing validator.
     *
     * @param Validator $validator
     */
    protected function configValidator(Validator $validator)
    {
        $validator->sometimes(
            'name',
            ['required'],
            function ($input) {
                return $input['type'] == CallbackTypeConstants::CALLBACK;
            }
        );
    }

    protected function getMessages()
    {
        return [
            'name.required' =>
                'Поле ' . trans('validation.attributes.full_name') . ' обязательно для заполнения.',
        ];
    }
}
