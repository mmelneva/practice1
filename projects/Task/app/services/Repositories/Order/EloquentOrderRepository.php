<?php namespace App\Services\Repositories\Order;

use App\Models\Order;
use App\Models\OrderDeliveryConstants;
use App\Models\OrderPaymentConstants;
use App\Models\OrderStatusConstants;
use App\Models\OrderTypeConstants;
use App\Services\Repositories\Generic\EloquentNamedModelRepository;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;

/**
 * Class EloquentOrderRepository
 * @package  App\Services\Repositories\Order
 */
class EloquentOrderRepository extends EloquentNamedModelRepository implements OrderRepositoryInterface
{
    public function __construct(EloquentAttributeToggler $attributeToggler, PossibleVariants $possibleVariants)
    {
        parent::__construct(new Order, $attributeToggler, $possibleVariants);
    }

    public function findByIdOrFail($id)
    {
        return $this->modelInstance->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        $query = Order::query();
        $this->scopeOrdered($query);

        return $query->get();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }


    public function byPage($page = 1, $limit = 20)
    {
        $query = Order::query();
        $this->scopeOrdered($query);

        $orderList = $query
            ->skip($limit * ($page - 1))
            ->take($limit)->get();

        $total = Order::count();

        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'items' => $orderList,
        ];
    }

    public function getDeliveryVariants()
    {
        $variants = [];
        foreach ([OrderDeliveryConstants::OWN_EXPENSE, OrderDeliveryConstants::COURIER] as $c) {
            $variants[$c] = trans("validation.model_attributes.order.delivery.{$c}");
        }

        return $variants;
    }

    public function getPaymentVariants()
    {
        $variants = [];
        foreach ([OrderPaymentConstants::CASH, OrderPaymentConstants::CASHLESS] as $c) {
            $variants[$c] = trans("validation.model_attributes.order.payment.{$c}");
        }

        return $variants;
    }

    public function getStatusVariants()
    {
        $variants = [];
        foreach (OrderStatusConstants::getConstants() as $c) {
            $variants[$c] = trans("validation.model_attributes.order.status.{$c}");
        }

        return $variants;
    }

    public function getTypeVariants()
    {
        $variants = [];
        foreach (OrderTypeConstants::getConstants() as $c) {
            $variants[$c] = trans("validation.model_attributes.order.type.{$c}");
        }

        return $variants;
    }

    public function findByCreatedHash($createdHash)
    {
        return $this->modelInstance->whereCreatedHash($createdHash)->first();
    }
}
