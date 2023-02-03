<?php namespace App\Models\Features;

/**
 * Class PasswordSetter
 * @package App\SocioCompass\Models\Features
 */
trait PasswordSetter
{
    /**
     * Hash the password on setting it.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }
}
