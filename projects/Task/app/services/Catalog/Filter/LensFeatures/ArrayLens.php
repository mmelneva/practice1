<?php namespace App\Services\Catalog\Filter\LensFeatures;

/**
 * Class ArrayLens
 * @package App\Services\Catalog\Filter\LensFeatures
 */
trait ArrayLens
{
    public function compareLensData($lensDataAlpha, $lensDataOmega)
    {
        $areArrays = is_array($lensDataAlpha) && is_array($lensDataOmega);
        if ($areArrays) {
            sort($lensDataAlpha);
            sort($lensDataOmega);
            if ($lensDataAlpha == $lensDataOmega) {
                return true;
            }
        }

        return false;
    }

    public function cleanLensData($query, $lensData)
    {
        return $lensData;
    }
}
