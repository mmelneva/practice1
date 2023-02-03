<?php namespace App\Services\Repositories\AdminUser;

use App\Services\Repositories\CreateUpdateRepositoryInterface;

/**
 * Interface AdminUserRepositoryInterface
 * @package App\Services\Repositories
 */
interface AdminUserRepositoryInterface extends CreateUpdateRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get new instance of user.
     *
     * @param array $data
     *
     * @return \App\Models\AdminUser
     */
    public function newInstance(array $data = []);

    /**
     * Find user by id.
     *
     * @param int $id
     * @return \App\Models\AdminUser|null
     */
    public function find($id);

    /**
     * Get all users without super users.
     *
     * @return mixed
     */
    public function allWithoutSuper();

    /**
     * Delete the user.
     *
     * @param $id
     * @return bool
     */
    public function delete($id);
}
