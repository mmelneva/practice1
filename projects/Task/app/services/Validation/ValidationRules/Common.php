<?php namespace App\Services\Validation\ValidationRules;

/**
 * Class Common
 * Common validation rules.
 * @package CustomValidator
 */
class Common
{
    /**
     * Check if value if subset of array.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateSubset($attribute, $value, $parameters)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                if (!in_array($v, $parameters)) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Validation which works like standard exists, but input data should be array and validation works with it keys.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateMultiKeyExists($attribute, $value, $parameters)
    {
        if (is_array($value)) {
            $keys = array_keys($value);
            foreach ($keys as $k) {
                $c = \DB::table($parameters[0])->where($parameters[1], $k)->count();
                if ($c != 1) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Validation which works like standard exists, but input data should be array and validation works with it values.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateMultiExists($attribute, $value, $parameters)
    {
        if (is_array($value)) {
            foreach ($value as $k) {
                $c = \DB::table($parameters[0])->where($parameters[1], $k)->count();
                if ($c != 1) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Check if value is phone.
     * Client side: public/assets/js/client/lib/jquery.validate/additional-methods_ext.js, rule: phoneNumber.
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return int
     */
    public function validatePhone($attribute, $value, $parameters, $validator)
    {
        return preg_match("@^\+7 \(\d{3}\) (\d{7})$@", $value);
    }

    /**
     * The field under validation must be more than value.
     *
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function validateMoreThan($attribute, $value, $parameters)
    {
        if (is_numeric($value)) {
            return $value > $parameters[0];
        }

        return false;
    }
}
