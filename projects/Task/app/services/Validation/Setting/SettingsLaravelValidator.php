<?php namespace App\Services\Validation\Setting;

use App\Services\Repositories\Setting\SettingRepositoryInterface;
use App\Services\Settings\SettingContainer;
use App\Services\Validation\AbstractLaravelValidator;
use Illuminate\Validation\Factory as ValidatorFactory;

/**
 * Class SettingsLaravelValidator
 * @package App\Services\Validation\Setting
 */
class SettingsLaravelValidator extends AbstractLaravelValidator
{
    private $settingRepository;
    private $settingContainer;

    public function __construct(
        ValidatorFactory $validatorFactory,
        SettingRepositoryInterface $settingRepository,
        SettingContainer $settingContainer
    ) {
        parent::__construct($validatorFactory);

        $this->settingRepository = $settingRepository;
        $this->settingContainer = $settingContainer;
    }

    private function getSettingLaravelValidator($settingKey, $value)
    {
        $validator = $this->validatorFactory->make(
            [$settingKey => $value],
            [$settingKey => $this->settingContainer->getRulesBy($settingKey)]
        );

        $validator->setAttributeNames([$settingKey => '']); // remove field key from error message

        return $validator;
    }

    public function passes()
    {
        return $this->passesSettings();
    }

    public function passesSettings()
    {
        $settings = array_get($this->data, 'setting', []);

        if (is_array($settings)) {
            $allPasses = true;
            foreach ($settings as $settingId => $value) {
                $settingKey = $this->settingRepository->getKeyById($settingId);
                if (!is_null($settingKey)) {
                    $settingLaravelValidator = $this->getSettingLaravelValidator($settingKey, $value);
                    $passes = $settingLaravelValidator->passes();

                    if (!$passes) {
                        $this->errors["setting.{$settingId}"] =
                            $settingLaravelValidator->messages()->toArray()[$settingKey];
                    }

                    $allPasses = $allPasses && $passes;
                }
            }
        } else {
            $allPasses = false;
        }

        return $allPasses;
    }
}
