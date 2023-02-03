<?php namespace App\Services\Repositories\Setting;

use App\Models\Setting;

/**
 * Class EloquentSettingRepository
 * @package  App\Services\Repositories\Setting
 */
class EloquentSettingRepository implements SettingRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return Setting::create($data);
    }

    /**
     * @inheritdoc
     */
    public function update($id, array $data)
    {
        /** @var Setting $object */
        $object = Setting::find($id);
        if (!is_null($object)) {
            $object->update($data);
        }

        return $object;
    }

    public function findByKey($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function newInstance(array $data = [])
    {
        return new Setting($data);
    }

    public function getKeyById($id)
    {
        return object_get(Setting::find($id), 'key');
    }

    public function getValueStyleById($id)
    {
        return object_get(Setting::find($id), 'value_style');
    }
}
