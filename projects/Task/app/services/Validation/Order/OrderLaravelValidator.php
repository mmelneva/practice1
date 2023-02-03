<?php namespace App\Services\Validation\Order;

use App\Services\Repositories\Order\OrderRepositoryInterface;
use App\Services\Validation\AbstractLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

/**
 * Class OrderLaravelValidator
 * @package App\Service\Validation\
 */
class OrderLaravelValidator extends AbstractLaravelValidator
{
    public function __construct(
        ValidatorFactory $validatorFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($validatorFactory);

        $this->orderRepository = $orderRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function getRules()
    {
        $rules = [
            'product_id' => 'sometimes|required|exists:catalog_products,id',
            'product_name' => 'sometimes|required',
            'phone' => ['required', 'phone'],
            'email' => 'email',
            'order_status' => ['in:' . implode(',', array_flip($this->orderRepository->getStatusVariants()))],
        ];

        return $rules;
    }

    protected function getMessages()
    {
        return [
            'name.required' =>
                'Поле ' . trans('validation.attributes.full_name') . ' обязательно для заполнения.',
        ];
    }
}
