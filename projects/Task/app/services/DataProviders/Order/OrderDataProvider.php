<?php namespace App\Services\DataProviders\Order;

use App\Models\Order;
use App\Services\Repositories\Order\OrderRepositoryInterface;

/**
 * Class OrderDataProvider
 * @package  App\Services\DataProviders
 */
class OrderDataProvider
{
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getPrintData(Order $order, $admin = false)
    {
        $orderData[trans('validation.attributes.order_status')] =
            trans("validation.model_attributes.order.status.{$order->order_status}");

        $orderData[trans('validation.attributes.full_name')] = $order->name;
        $orderData[trans('validation.attributes.phone')] = $order->phone;
        $orderData[trans('validation.attributes.email')] = $order->email;

        if (!is_null($order->product)) {
            $orderData[trans('validation.model_attributes.order.product')] =
                '<a href="' . \UrlBuilder::getUrl($order->product) . '">' . $order->product->name . '</a>';

            if ($admin) {
                $orderData[trans('validation.model_attributes.order.product')] .= ' (<a href="' .
                    action('App\Controllers\Admin\CatalogProductsController@getEdit', [$order->product->id]) .
                    '">cc</a>)';
            }
        }
        $orderData[trans('validation.attributes.comment')] = $order->comment;

        $orderData = array_filter(
            $orderData,
            function ($v) {
                $v = trim($v);

                return !empty($v);
            }
        );

        return $orderData;
    }

    public function getOrderFormData()
    {
        return ['order_status_list' => $this->orderRepository->getStatusVariants()];
    }
}
