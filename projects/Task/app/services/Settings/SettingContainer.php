<?php
namespace App\Services\Settings;

use App\Services\Settings\Exception\NotFoundKeyException;
use App\Services\Settings\Exception\NotUniqueKeyException;

/**
 * Class SettingContainer
 * Container to manage config which will be sent in database.
 * @package App\Service\Settings
 */
class SettingContainer
{
    /** @var SettingGroup[] */
    private $settingGroupList = [];

    /**
     * Add group of settings.
     * @param SettingGroup $settingGroup
     * @throws NotUniqueKeyException
     */
    public function addSettingGroup(SettingGroup $settingGroup)
    {
        if ($this->checkKeyAreUnique($settingGroup)) {
            $this->settingGroupList[] = $settingGroup;
        } else {
            throw new NotUniqueKeyException;
        }
    }

    /**
     * Check if adding keys are unique.
     * @param SettingGroup $settingGroup
     * @return bool
     */
    private function checkKeyAreUnique(SettingGroup $settingGroup)
    {
        $unique = true;
        $existingKeys = $this->getKeyArray();

        foreach ($settingGroup->getSettingValueList() as $value) {
            if (in_array($value->getKey(), $existingKeys)) {
                $unique = false;
                break;
            }
        }

        return $unique;
    }

    /**
     * Get group of settings.
     * @return SettingGroup[]
     */
    public function getSettingGroupList()
    {
        return $this->settingGroupList;
    }

    /**
     * Get array of keys.
     * @return array
     */
    public function getKeyArray()
    {
        $keyList = [];
        foreach ($this->settingGroupList as $group) {
            foreach ($group->getSettingValueList() as $value) {
                $keyList[] = $value->getKey();
            }
        }

        return $keyList;
    }

    /**
     * Get array of settings.
     * @return SettingValue[]
     */
    public function getSettingList()
    {
        $settingList = [];

        foreach ($this->settingGroupList as $group) {
            foreach ($group->getSettingValueList() as $value) {
                $settingList[] = $value;
            }
        }

        return $settingList;
    }

    /**
     * Get rules for setting value by key.
     *
     * @param $key
     * @return string
     * @throws NotFoundKeyException
     */
    public function getRulesBy($key)
    {
        $settingList = $this->getSettingList();

        foreach ($settingList as $setting) {
            if ($setting->getKey() == $key) {
                return $setting->getRules();

            }
        }

        throw new NotFoundKeyException;
    }

}
