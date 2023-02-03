<?php namespace App\Models\Features;

/**
 * Class ConstantsGetter
 * @package  App\Models\Features
 */
trait ConstantsGetter
{
    public static function getConstants()
    {
        return with(new \ReflectionClass(get_called_class()))->getConstants();
    }
}
