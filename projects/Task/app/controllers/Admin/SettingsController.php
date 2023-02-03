<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\FormProcessors\SettingsFormProcessor;
use App\Services\Repositories\Setting\SettingRepositoryInterface;
use App\Services\Repositories\SettingGroup\SettingGroupRepositoryInterface;
use App\Services\Settings\SettingContainer;

/**
 * Class SettingsController
 * Controller to manage settings.
 * @package Admin
 */
class SettingsController extends BaseController
{
    /**
     * @var SettingGroupRepositoryInterface
     */
    private $settingGroupRepo;
    /**
     * @var SettingRepositoryInterface
     */
    private $settingRepo;

    /**
     * @var SettingsFormProcessor
     */
    private $settingsFormProcessor;

    /**
     * @var SettingContainer
     *
     **/
    private $settingContainer;

    public function __construct(
        SettingGroupRepositoryInterface $settingGroupRepo,
        SettingRepositoryInterface $settingRepo,
        SettingsFormProcessor $settingsFormProcessor,
        SettingContainer $settingContainer
    ) {
        $this->settingGroupRepo = $settingGroupRepo;
        $this->settingRepo = $settingRepo;
        $this->settingsFormProcessor = $settingsFormProcessor;
        $this->settingContainer = $settingContainer;
    }

    public function getIndex()
    {
        $settingGroupList = $this->settingGroupRepo->all();

        if (count($settingGroupList) == 0) {
            // fill settings if they aren't exist
            \App::make('artisan')->call('db:seed', ['--class' => 'SettingsSeeder']);
            $settingGroupList = $this->settingGroupRepo->all();
        }

        return \View::make('admin.settings.index')->with('group_list', $settingGroupList);
    }

    public function putUpdateAll()
    {
        $this->settingsFormProcessor->updateAll(\Input::all());
        $errors = $this->settingsFormProcessor->errors();

        if (count($errors) > 0) {
            return \Redirect::action(get_called_class() . '@getIndex')->withErrors($errors)->withInput();

        } else {
            return \Redirect::action(get_called_class() . '@getIndex')
                    ->with('alert_success', 'Изменения сохранены');
        }
    }
}
