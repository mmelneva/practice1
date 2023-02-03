<?php namespace App\Services\FormProcessors;

use App\Services\Repositories\Setting\SettingRepositoryInterface;
use App\Services\Settings\SettingContainer;
use App\Services\Settings\SettingValue;
use App\Services\Validation\Setting\SettingsLaravelValidator;

/**
 * Class SettingsFormProcessor
 * @property SettingRepositoryInterface $repository
 * @package App\Services\FormProcessors
 */
class SettingsFormProcessor extends CreateUpdateFormProcessor
{
    private $settingContainer;

    public function __construct(
        SettingsLaravelValidator $validator,
        SettingRepositoryInterface $repository,
        SettingContainer $settingContainer
    ) {
        parent::__construct($validator, $repository);
        $this->settingContainer = $settingContainer;
    }

    protected function prepareInputData(array $data)
    {
        $settingList = array_get($data, 'setting');

        foreach ($settingList as $settingId => $value) {
            $settingKey = $this->repository->getKeyById($settingId);
            $valueStyle = $this->repository->getValueStyleById($settingId);

            if (!is_null($settingKey)) {
                $rules = $this->settingContainer->getRulesBy($settingKey);
                if (in_array('email_list', $rules)) {
                    $settingList[$settingId] = implode(',', explode_with_trim($value));
                }
            }

            if ($valueStyle == SettingValue::TYPE_REDIRECTS) {
                $rules = [];

                foreach ($value as $item) {
                    $rules[$item['rule']] = $item['url'];
                }

                $settingList[$settingId] = $rules;
            }
        }
        array_set($data, 'setting', $settingList);

        return $data;
    }

    public function updateAll(array $data = [])
    {
        $data = $this->prepareInputData($data);
        if ($this->validator->with($data)->passes()) {
            foreach (array_get($data, 'setting') as $id => $value) {
                $this->repository->update($id, ['value' => is_array($value) ? json_encode($value) : $value]);
            }
        }
    }
}
