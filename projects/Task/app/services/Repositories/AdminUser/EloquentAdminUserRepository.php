<?php namespace App\Services\Repositories\AdminUser;

use App\Models\AdminUser;

/**
 * Class EloquentAdminUserRepository
 * @package App\Services\Repositories\AdminUser
 */
class EloquentAdminUserRepository implements AdminUserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all()
    {
        return AdminUser::orderBy('username')->get();
    }

    /**
     * @inheritDoc
     */
    public function allWithoutSuper()
    {
        return AdminUser::orderBy('username')->where('super', false)->get();
    }

    /**
     * @inheritDoc
     */
    public function newInstance(array $data = [])
    {
        return new AdminUser($data);
    }

    /**
     * @inheritDoc
     */
    public function find($id)
    {
        return AdminUser::find($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return AdminUser::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data)
    {
        /** @var AdminUser $adminUser */
        $adminUser = AdminUser::find($id);
        if (!is_null($adminUser)) {
            $adminUser->update($data);
        }

        return $adminUser;
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        /**
         * @var AdminUser $adminUser
         */
        $adminUser = AdminUser::find($id);
        if (!is_null($adminUser)) {
            $adminUser->delete();
            return true;
        } else {
            return false;
        }
    }
}
