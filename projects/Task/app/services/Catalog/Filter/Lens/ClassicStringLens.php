<?php namespace App\Services\Catalog\Filter\Lens;

use App\Services\Catalog\Filter\LensFeatures\ArrayLens;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Models\Attribute;

/**
 * Class ClassicStringLens
 * Lens to search by additional attribute with string type.
 *
 * @package App\Services\Catalog\Filter\Lens
 */
class ClassicStringLens implements LensInterface
{
    use ArrayLens;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attrRepo;

    /**
     * @var \App\Models\Attribute|null
     */
    private $attribute;

    /**
     * ClassicStringLens constructor.
     * @param AttributeRepositoryInterface $attrRepo
     * @param Attribute $attribute
     */
    public function __construct(
        AttributeRepositoryInterface $attrRepo,
        Attribute $attribute
    ) {
        $this->attrRepo = $attrRepo;
        $this->attribute = $attribute;
    }

    public function modifyQuery($query, $lensData)
    {
        if (!is_array($lensData)) {
            $lensData = [];
        }

        if (count($lensData) === 0) {
            return;
        }

        $attribute = $this->attribute;
        if (is_null($attribute)) {
            return;
        }
        $attrId = $attribute->id;
        $attrValuesAlias = "attr_val_{$attrId}";

        $query->join(
            "attribute_values AS {$attrValuesAlias}",
            function ($join) use ($attrId, $attrValuesAlias) {
                $join->on(
                    'catalog_products.id',
                    '=',
                    "{$attrValuesAlias}.product_id"
                )->where("{$attrValuesAlias}.attribute_id", '=', $attrId);
            }
        )->whereIn("{$attrValuesAlias}.value", $lensData);
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

        $allowedValues = $query->join('attribute_values', 'attribute_values.product_id', '=', 'catalog_products.id')
            ->where('attribute_values.attribute_id', $attribute->id)
            ->whereNotNull('attribute_values.value')
            ->where('attribute_values.value', '<>', '')
            ->select('attribute_values.value')
            ->orderBy('attribute_values.value')
            ->distinct()
            ->lists('attribute_values.value');

        $variants = [];
        foreach ($allowedValues as $value) {
            $variants[] = [
                'value' => $value,
                'title' => $value,
                'checked' => in_array($value, $lensData)
            ];
        }

        if (count($variants) === 0) {
            $variants = null;
        }

        return $variants;
    }
}
