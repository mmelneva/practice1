<?php namespace App\Services\Catalog\Filter\Lens;

use App\Models\Attribute;
use App\Services\Catalog\Filter\LensFeatures\RangeLens as RangeLensFeature;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;

/**
 * Class RangeLens
 * Lens to search by additional attribute with string type but like by range.
 *
 * @package App\Services\Catalog\Filter\Lens
 */
class RangeLens implements LensInterface
{
    use RangeLensFeature;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attrRepo;

    /**
     * @var int
     */
    private $decimals;

    /**
     * @var \App\Models\Attribute|null
     */
    private $attribute;

    /**
     * RangeLens constructor.
     * @param AttributeRepositoryInterface $attrRepo
     * @param Attribute $attribute
     * @param int $decimals
     */
    public function __construct(AttributeRepositoryInterface $attrRepo, Attribute $attribute, $decimals = 1)
    {
        $this->attrRepo = $attrRepo;
        $this->attribute = $attribute;
        $this->decimals = $decimals;
    }

    /**
     * Get attribute.
     *
     * @return \App\Models\Attribute|null
     */
    public function modifyQuery($query, $lensData)
    {
        if (empty($lensData)) {
            return;
        }

        $attribute = $this->attribute;
        if (is_null($attribute)) {
            return;
        }
        $attrId = $attribute->id;

        $from = array_get($lensData, 'from');
        $to = array_get($lensData, 'to');
        $avAlias = "av_{$attrId}";

        if (!is_null($from) && !is_null($to)) {
            $compareData = ['BETWEEN ? AND ?', [$from, $to]];
        } elseif (!is_null($from)) {
            $compareData = ['>= ?', [$from]];
        } else {
            $compareData = ['<= ?', [$to]];
        }

        $query
            ->join("attribute_values AS {$avAlias}", "{$avAlias}.product_id", '=', 'catalog_products.id')
            ->where("{$avAlias}.attribute_id", $attrId)
            ->whereRaw(
                "CAST(REPLACE({$avAlias}.value, ',', '.') AS DECIMAL(10, {$this->decimals})) {$compareData[0]}",
                $compareData[1]
            );
    }

    public function getVariants($query, $lensData)
    {
        if (!is_array($lensData)) {
            $lensData = [];
        }

        $attribute = $this->attribute;
        if (is_null($attribute)) {
            return null;
        }

        $result = $query->getQuery()
            ->leftJoin('attribute_values', 'attribute_values.product_id', '=', 'catalog_products.id')
            ->where('attribute_values.attribute_id', $attribute->id)
            ->selectRaw(
                "MIN(CAST(REPLACE(attribute_values.value, ',', '.') AS DECIMAL(10, {$this->decimals}))) as min" .
                ', ' .
                "MAX(CAST(REPLACE(attribute_values.value, ',', '.') AS DECIMAL(10, {$this->decimals}))) as max"
            )
            ->first();

        $min = $result->min;
        $max = $result->max;

        $fromValue = array_get($lensData, 'from', $min);
        $toValue = array_get($lensData, 'to', $max);

        if ($min === $max) {
            return null;
        } else {
            return [
                'min' => $min,
                'max' => $max,
                'from' => $fromValue,
                'to' => $toValue,
                'decimals' => $this->decimals
            ];
        }
    }
}
