<?php namespace App\Services\Validation\ValidationRules;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Factory as ValidatorFactory;

/**
 * Class IpValidationRule
 * @package App\SocioCompass\Validation\ValidationRules
 */
class IpValidationRule
{
    /** @var ValidatorFactory */
    private $validatorFactory;

    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Ip list validation.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validateAllowedIpList($attribute, $value, $parameters, Validator $validator)
    {
        if (is_array($value)) {
            $ret = true;
            foreach ($value as $key => $ip) {
                $ipValidator = $this->validatorFactory->make(
                    [$attribute => $ip],
                    [$attribute => 'ip']
                );
                if ($ipValidator->fails()) {
                    $message = $ipValidator->errors()->get($attribute)[0];
                    $validator->getMessageBag()->add($attribute . '_' . $key, $message);
                    $ret = false;
                }
            }
            return $ret;
        } else {
            $validator->getMessageBag()->add($attribute, 'Wrong IP list format');
            return false;
        }
    }
}
