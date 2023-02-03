<?php namespace App\Services\Catalog\Filter\Lens;

use App\Services\Catalog\Filter\LensFeatures\ArrayLens;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\AttributeAllowedValue\AttributeAllowedValueRepositoryInterface;
use App\Models\Attribute;

/**
 * Class ClassicListLens
 * Lens to search by additional attribute with list type.
 *
 * @package App\Services\Catalog\Filter\Lens
 */
class ClassicListLens implements LensInterface
{
    use ArrayLens;

    /**
     * @var AttributeAllowedValueRepositoryInterface
     */
    private $allowedValueRepo;
    /**
     * @var AttributeRepositoryInterface
     */
    private $attrRepo;

    /**
     * @var \App\Models\Attribute|null
     */
    private $attribute;

    /**
     * ClassicListLens constructor.
     * @param AttributeRepositoryInterface $attrRepo
     * @param AttributeAllowedValueRepositoryInterface $allowedValueRepo
     * @param Attribute $attribute
     */
    public function __construct(
        AttributeRepositoryInterface $attrRepo,
        AttributeAllowedValueRepositoryInterface $allowedValueRepo,
        Attribute $attribute
    ) {
        $this->allowedValueRepo = $allowedValueRepo;
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

        $query->leftJoin(
            "attribute_values AS {$attrValuesAlias}",
            function ($join) use ($attrId, $attrValuesAlias) {
                $join->on(
                    'catalog_products.id',
                    '=',
                    "{$attrValuesAlias}.product_id"
                )->where("{$attrValuesAlias}.attribute_id", '=', $attrId);
            }
        )->whereIn("{$attrValuesAlias}.allowed_value_id", $lensData);
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

        $allowedIds = $query->join('attribute_values', 'attribute_values.product_id', '=', 'catalog_products.id')
            ->where('attribute_values.attribute_id', $attribute->id)
            ->select('attribute_values.allowed_value_id')
            ->distinct()
            ->lists('allowed_value_id');
        $allowedValues = $this->allowedValueRepo->allWithIds($allowedIds);

        $variants = [];
        foreach ($allowedValues as $value) {
            $variants[] = [
                'value' => $value->id,
                'title' => $value->value,
                'checked' => in_array($value->id, $lensData)
            ];
        }

        if (count($variants) === 0) {
            $variants = null;
        }

        return $variants;
    }
}
