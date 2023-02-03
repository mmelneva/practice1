<?php namespace App\Services\Repositories\Setting;

use App\Services\Repositories\CreateUpdateRepositoryInterface;
/**
 * Interface SettingRepositoryInterface
 * @package App\Services\Repositories\Setting
 */
interface SettingRepositoryInterface extends CreateUpdateRepositoryInterface
{
    public function create(array $data);

    public function update($id, array $data);

    public function findByKey($key);

    public function newInstance(array $data = []);

    public function getKeyById($id);

    public function getValueStyleById($id);
}
