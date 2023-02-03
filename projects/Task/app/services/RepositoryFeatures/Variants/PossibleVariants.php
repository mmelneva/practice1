<?php namespace App\Services\RepositoryFeatures\Variants;

class PossibleVariants
{
    private $nullVariantValue;

    public function __construct($nullVariantValue = null)
    {
        if (is_null($nullVariantValue)) {
            $nullVariantValue = trans('validation.attributes.not_chosen');
        }

        $this->nullVariantValue = $nullVariantValue;
    }

    public function getVariants($elements, $nullVariant = false)
    {
        $result = [];

        if ($nullVariant) {
            $result[0] = $this->nullVariantValue;
        }

        foreach ($elements as $model) {
            $result[$model['id']] = $model['name'];
        }

        return $result;
    }
}
