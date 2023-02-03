<?php namespace App\Services\RepositoryFeatures\Order;

/**
 * Interface OrderScopesInterface
 * @package App\Services\RepositoryFeatures\Order
 */
interface OrderScopesInterface
{
    /**
     * Make query ordered.
     *
     * @param $query
     * @return mixed
     */
    public function scopeOrdered($query);
}
