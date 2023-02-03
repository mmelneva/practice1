<?php namespace App\Services\Repositories\AdminRole;

use App\Models\AdminRole;

/**
 * Class EloquentAdminRoleRepository
 * @package App\Services\Repositories\AdminRole
 */
class EloquentAdminRoleRepository implements AdminRoleRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function all()
    {
        return AdminRole::orderBy('name')->get();
    }

    public function allByIds(array $ids)
    {
        if (count($ids) === 0) {
            $ids = [null];
        }

        return AdminRole::orderBy('name')->whereIn('id', $ids)->get();
    }


    /**
     * @inheritDoc
     */
    public function newInstance(array $data = [], $exists = false)
    {
        return new AdminRole($data);
    }

    /**
     * @inheritDoc
     */
    public function findById($id)
    {
        return AdminRole::find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        /** @var AdminRole $role */
        $role = AdminRole::find($id);
        if (!is_null($role)) {
            $role->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return AdminRole::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data)
    {
        /** @var AdminRole $adminRole */
        $adminRole = AdminRole::find($id);
        if (!is_null($adminRole)) {
            $adminRole->update($data);
        }

        return $adminRole;
    }

    /**
     * @inheritDoc
     */
    public function getVariants($nullVariant = false)
    {
        $result = [];
        if ($nullVariant) {
            $result[0] = '';
        }

        foreach (AdminRole::orderBy('name')->lists('name', 'id') as $id => $name) {
            $result[$id] = $name;
        }

        return $result;
    }
}
