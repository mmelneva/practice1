<?php namespace App\Services\Repositories\Callback;

use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\PaginateListRepositoryInterface;

/**
 * Interface CallbackRepositoryInterface
 * @package App\Services\Repositories\Callback
 */
interface CallbackRepositoryInterface extends PaginateListRepositoryInterface, CreateUpdateRepositoryInterface
{
    public function findByIdOrFail($id);

    public function getStatusVariants();

    public function getTypeVariants();
}
