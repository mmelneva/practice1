<?php
namespace App\Services\Settings;

use App\Models\Setting;

/**
 * Class SettingGetter
 * @package Settings
 */
class SettingGetter
{
    /**
     * @var array
     */
    private $settingList = [];

    public function __construct(SettingContainer $settingContainer, Setting $settingRepo)
    {
        foreach ($settingContainer->getSettingList() as $setting) {
            $this->settingList[$setting->getKey()] = $setting->getDefaultValue();
        }

        foreach ($settingRepo->all() as $settingModel) {
            if (array_key_exists($settingModel->key, $this->settingList)) {
                $this->settingList[$settingModel->key] = $settingModel->value;
            }
        }
    }

    /**
     * Get setting.
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->settingList[$key];
    }

    /**
     * Get setting as array.
     *
     * @param $key
     * @param $delimiter
     * @return array
     */
    public function getAsArray($key, $delimiter = ',')
    {
        return explode_with_trim($this->get($key), $delimiter);
    }
}
