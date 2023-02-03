<?php namespace App\Services\Repositories\SettingGroup;

use App\Models\SettingGroup;

/**
 * Class EloquentSettingRepository
 * @package  App\Services\Repositories\Setting
 */
class EloquentSettingGroupRepository implements SettingGroupRepositoryInterface
{
    public function all()
    {
        $with = [
            'settings' => function ($query) {
                $query->orderBy('settings.position');
            }
        ];

        return SettingGroup::orderBy('setting_groups.position')->with($with)->get();
    }
}
