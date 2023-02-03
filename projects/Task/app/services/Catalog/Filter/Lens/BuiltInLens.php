<?php namespace App\Services\Catalog\Filter\Lens;

use App\Models\ProductBuiltInConstants;

/**
 * Class BuiltInLens
 *
 * @package App\Services\Catalog\Filter\Lens
 */
class BuiltInLens implements LensInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @param string $field
     */
    public function __construct($field = 'built_in')
    {
        $this->field = $field;
    }


    public function modifyQuery($query, $lensData)
    {
        if (isset($lensData) && is_string($lensData)) {
            $query->where("catalog_products.{$this->field}", $lensData);
        }
    }

    public function getVariants($query, $lensData)
    {
        if (!is_string($lensData)) {
            $lensData = null;
        }

        $builtInVariants = $this->getBuiltInVariants();

        $variants = [];
        foreach ($builtInVariants as $value => $name) {
            $variants[] = [
                'value' => $value,
                'title' => $name,
                'checked' => !is_null($lensData) && $value == $lensData,
            ];
        }

        if (count($variants) === 0) {
            $variants = null;
        }

        return $variants;
    }

    public function cleanLensData($query, $lensData)
    {
        return $lensData;
    }

    public function compareLensData($lensDataAlpha, $lensDataOmega)
    {
        return $lensDataAlpha == $lensDataOmega;
    }

    private function getBuiltInVariants()
    {
        $variants = [];

        foreach (ProductBuiltInConstants::getConstants() as $c) {
            $variants[$c] = trans("validation.model_attributes.catalog_product.built_in.{$c}");
        }

        return $variants;
    }
}
