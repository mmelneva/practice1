<?php namespace App\Services\Repositories\AttributeValue;

use App\Models\Attribute;
use App\Models\AttributeValue;

/**
 * Class EloquentAttributeValueRepository
 * @package  App\Services\Repositories\AttributeValue
 */
class EloquentAttributeValueRepository implements AttributeValueRepositoryInterface
{
    public function __construct()
    {
        $this->modelInstance = new AttributeValue;
    }

    public function newInstance(array $attributes = array(), $exists = false)
    {
        return $this->modelInstance->newInstance($attributes, $exists);
    }

    public function createOrUpdate($attributeId, $productId, array $attributeValueData = [])
    {
        $existingAttributeValue = $this->modelInstance
            ->where('attribute_id', $attributeId)
            ->where('product_id', $productId)
            ->first();

        if (is_null($existingAttributeValue)) {
            /** @var AttributeValue $existingAttributeValue */
            $existingAttributeValue = $this->modelInstance->newInstance();

            $existingAttributeValue->attribute_id = $attributeId;
            $existingAttributeValue->product_id = $productId;
        }

        $existingAttributeValue->fill($attributeValueData);
        $existingAttributeValue->save();

        $allowedValueIdList = array_get($attributeValueData, 'allowed_value_id_list', []);
        if (count($allowedValueIdList) > 0) {
            $existingAttributeValue->multipleValues()->sync($allowedValueIdList);
        }

        return $existingAttributeValue;
    }

    public function delete($attributeId, $productId)
    {
        $attributeValue = $this->modelInstance
            ->where('attribute_id', $attributeId)
            ->where('product_id', $productId)->first();

        if (!is_null($attributeValue)) {
            $attributeValue->delete();
        }
    }

    public function getValuesForProducts($productList, $attributeList)
    {
        $productIdList = [];
        foreach ($productList as $product) {
            if(is_object($product)) {
                $productIdList[] = $product->id;
            }
        }
        if (count($productIdList) === 0) {
            $productIdList = [null];
        }

        $attributeIdList = [];
        foreach ($attributeList as $attribute) {
            $attributeIdList[] = $attribute->id;
        }
        if (count($attributeIdList) === 0) {
            $attributeIdList = [null];
        }

        $values = AttributeValue::whereIn('attribute_id', $attributeIdList)
            ->whereIn('product_id', $productIdList)
            ->with('attribute')
            ->get();

        return $values;
    }

    public function getValuesForProduct($productId, $attributeList)
    {
        $attributeIdList = [];
        foreach ($attributeList as $attribute) {
            $attributeIdList[] = $attribute->id;
        }
        if (count($attributeIdList) === 0) {
            $attributeIdList = [null];
        }

        $values = AttributeValue::whereIn('attribute_id', $attributeIdList)
            ->where('product_id', $productId)
            ->with('attribute')
            ->get();

        return $values;
    }

    public function getValueVariant(AttributeValue $attributeValue)
    {
        static $valueVariants;

        $valueVariantKey = $this->getValueVariantUid($attributeValue);

        if (!isset($valueVariants[$valueVariantKey])) {
            $valueVariant = $attributeValue->getValueVariant();

            if (!is_null($valueVariant)) {
                $valueVariants[$valueVariantKey] = $valueVariant;
            }
        }

        return array_get($valueVariants, $valueVariantKey);
    }

    private function getValueVariantUid(AttributeValue $attributeValue)
    {
        if ($attributeValue->attribute->type == Attribute::TYPE_MULTIPLE_VALUES) {
            $fieldsInKey = ['attribute_id', 'product_id'];
        } else {
            $fieldsInKey = ['value', 'allowed_value_id', 'attribute_id'];
        }

        $pairs = [];
        foreach ($fieldsInKey as $field) {
            $pairs[] = "{$field}:{$attributeValue->{$field}}";
        }

        return md5(implode('.', $pairs));
    }
}
