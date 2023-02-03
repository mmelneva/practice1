<?php namespace App\Models\Features;

/**
 * Class ArrayAttribute
 * @package App\SocioCompass\Models\Features
 */
trait ArrayAttribute
{
    /**
     * Set array attribute (convert to json).
     *
     * @param $field
     * @param $value
     * @return string
     */
    protected function setArrayAttribute($field, $value)
    {
        $this->attributes[$field] = json_encode($value);
        return $value;
    }

    /**
     * Get array attribute (convert from json).
     *
     * @param $field
     * @return array
     */
    protected function getArrayAttribute($field)
    {
        $ret = [];
        if (isset($this->attributes[$field])) {
            $extracted = json_decode($this->attributes[$field], true);
            if (is_array($extracted)) {
                $ret = $extracted;
            }
        }
        return $ret;
    }
}
