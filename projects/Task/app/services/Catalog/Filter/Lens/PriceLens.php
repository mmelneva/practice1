<?php namespace App\Services\Catalog\Filter\Lens;

use App\Services\Catalog\Filter\LensFeatures\RangeLens;

/**
 * Class PriceLens
 * Lens to filter by price (range).
 *
 * @package App\Services\Catalog\Filter\Lens
 */
class PriceLens implements LensInterface
{
    use RangeLens;

    public function modifyQuery($query, $lensData)
    {
        $from = array_get($lensData, 'from');
        $to = array_get($lensData, 'to');

        if (!is_null($from)) {
            $query->where('price', '>=', $from);
        }

        if (!is_null($to)) {
            $query->where('price', '<=', $to);
        }
    }

    public function getVariants($query, $lensData)
    {
        $query->where('price', '>', '0')->whereNotNull('price');

        $minQuery = clone $query;
        $maxQuery = clone $query;

        $min = floor($minQuery->selectRaw('MIN(price) AS min')->pluck('min'));
        $max = ceil($maxQuery->selectRaw('MAX(price) AS max')->pluck('max'));

        $fromValue = array_get($lensData, 'from', $min);
        $toValue = array_get($lensData, 'to', $max);

        if ($min === $max) {
            return null;
        } else {
            return ['min' => $min, 'max' => $max, 'from' => $fromValue, 'to' => $toValue];
        }
    }
}
