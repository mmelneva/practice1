<?php namespace App\Services\RepositoryFeatures\Order;

/**
 * Class PositionOrderScopes
 * @package App\Services\RepositoryFeatures\Order
 */
class PositionOrderScopes implements OrderScopesInterface
{
    /**
     * @inheritDoc
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
