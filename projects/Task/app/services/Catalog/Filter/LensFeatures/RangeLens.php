<?php namespace App\Services\Catalog\Filter\LensFeatures;

trait RangeLens
{
    public function cleanLensData($query, $lensData)
    {
        $variants = $this->getVariants($query, $lensData);

        $cleanLensData = [];

        if (!is_null($lensData)) {
            if ($variants['min'] != $variants['from']) {
                $cleanLensData['from'] = $variants['from'];
            }

            if ($variants['max'] != $variants['to']) {
                $cleanLensData['to'] = $variants['to'];
            }
        }

        if (empty($cleanLensData)) {
            $cleanLensData = null;
        }

        return $cleanLensData;
    }

    public function compareLensData($lensDataAlpha, $lensDataOmega)
    {
        $alphaFrom = array_get($lensDataAlpha, 'from');
        $alphaTo = array_get($lensDataAlpha, 'to');
        $omegaFrom = array_get($lensDataOmega, 'from');
        $omegaTo = array_get($lensDataOmega, 'to');

        return $alphaFrom == $omegaFrom && $alphaTo == $omegaTo;
    }
}
