<?php
namespace App\Services\Settings;

/**
 * Class SettingGroup
 * @package App\Service\Settings
 */
class SettingGroup
{
    /** @var string */
    private $groupName;
    /** @var SettingValue[] */
    private $settingValueList = [];

    /**
     * @param string $groupName
     */
    public function __construct($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * Add setting value.
     * @param SettingValue $settingValue
     */
    public function addSettingValue(SettingValue $settingValue)
    {
        $this->settingValueList[] = $settingValue;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * @return SettingValue[]
     */
    public function getSettingValueList()
    {
        return $this->settingValueList;
    }
}
