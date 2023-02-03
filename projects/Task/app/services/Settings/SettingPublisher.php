<?php
namespace App\Services\Settings;

use App\Models\Setting;
use App\Models\SettingGroup;

class SettingPublisher
{
    private $settingContainer;
    private $settingGroup;
    private $setting;

    public function __construct(SettingContainer $settingContainer, SettingGroup $settingGroup, Setting $setting)
    {
        $this->settingContainer = $settingContainer;
        $this->settingGroup = $settingGroup;
        $this->setting = $setting;
    }

    public function publish()
    {
        $existingGroupIds = [];
        $existingValueIds = [];

        $groupList = $this->settingContainer->getSettingGroupList();
        foreach ($groupList as $groupNumber => $group) {
            // find or create group model
            $groupModel = $this->settingGroup->where('name', $group->getGroupName())->first();
            if (is_null($groupModel)) {
                $groupModel = $this->settingGroup->create(['name' => $group->getGroupName()]);
            }
            $groupModel->fill(['position' => $groupNumber]);
            $groupModel->save();

            $existingGroupIds[] = $groupModel->id;

            foreach ($group->getSettingValueList() as $valueNumber => $value) {
                // Find or create values
                /** @var Setting $valueModel */
                $valueModel = $this->setting->where('key', $value->getKey())->first();
                if (is_null($valueModel)) {
                    $valueModel = $this->setting->newInstance(
                        [
                            'key' => $value->getKey(),
                            'value' => $value->getDefaultValue(),
                        ]
                    );
                }

                $valueModel->fill(
                    [
                        'title' => $value->getName(),
                        'position' => $valueNumber,
                        'description' => $value->getDescription(),
                        'value_style' => $value->getType(),
                    ]
                );
                $valueModel->group()->associate($groupModel);
                $valueModel->save();

                $existingValueIds[] = $valueModel->id;
            }
        }

        // Delete rest of values and groups
        $this->setting->whereNotIn('id', $existingValueIds)->delete();
        $this->settingGroup->whereNotIn('id', $existingGroupIds)->delete();
    }
}
