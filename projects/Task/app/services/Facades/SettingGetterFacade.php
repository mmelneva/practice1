<?php
namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SettingGetterFacade
 * Facade for setting getter.
 * @package Facade
 */
class SettingGetterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'setting_getter';
    }
}
