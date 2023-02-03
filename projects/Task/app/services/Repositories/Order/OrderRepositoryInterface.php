<?php  namespace App\Services\Repositories\Order;

use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\PaginateListRepositoryInterface;

/**
 * Interface OrderRepositoryInterface
 * @package App\Services\Repositories\Order
 */
interface OrderRepositoryInterface extends PaginateListRepositoryInterface, CreateUpdateRepositoryInterface
{
    public function findByIdOrFail($id);

    public function getDeliveryVariants();

    public function getPaymentVariants();

    public function getStatusVariants();

    public function getTypeVariants();

    public function findByCreatedHash($createdHash);
}
