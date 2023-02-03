<?php namespace App\Services\DataProviders\CatalogProduct;

use App\Models\Attribute;
use App\Models\AttributeAllowedValue;
use App\Models\AttributeValue;
use App\Models\CatalogProduct;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class AdditionalAttributesDataProvider
 * @package  App\Services\DataProviders\CatalogProduct
 */
class AdditionalAttributesDataProvider
{
    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->catalogProductRepository = $catalogProductRepository;
        $this->attributeRepository = $attributeRepository;
    }

    public function getAdditionalAttributesFormData(CatalogProduct $product, array $oldData = [])
    {
        $oldVariants = $this->getOldAttributeVariants($oldData);

        $enabledVariants = $this->getEnabledAttributeVariants($product);
        $disabledVariants = $this->getDisabledAttributeVariants($product);

        $enabledAttributes = [];
        $disabledAttributes = [];

        foreach ($this->attributeRepository->all()->lists('id') as $attributeId) {
            if (isset($oldVariants[$attributeId])) {
                $enabledAttributes[] = $oldVariants[$attributeId];
            }
            if (isset($enabledVariants[$attributeId]) && !isset($oldVariants[$attributeId])) {
                $enabledAttributes[] = $enabledVariants[$attributeId];
            }
            if (isset($disabledVariants[$attributeId]) && !isset($oldVariants[$attributeId])) {
                $disabledAttributes[] = $disabledVariants[$attributeId];
            }
        }

        return [
            'additional_attributes_enabled' => $enabledAttributes,
            'additional_attributes_disabled' => $disabledAttributes
        ];
    }

    /**
     * @param CatalogProduct $product
     * @return array
     */
    private function getEnabledAttributeVariants(CatalogProduct $product)
    {
        $variants = [];

        $attributeValues = $product->attributeValues()->where('product_id', $product->id)->get();

        /** @var AttributeValue $attributeValue */
        foreach ($attributeValues as $attributeValue) {
            $attributeId = $attributeValue->attribute_id;

            /** @var Attribute $attribute */
            $attribute = $attributeValue->attribute;
            $attributeType = $attributeValue->attribute->type;
            $value = $attributeValue->getValueVariant();

            $variantData = ['attribute' => $attribute];

            switch ($attributeType) {
                case Attribute::TYPE_MULTIPLE_VALUES:
                    $variantData['variants'] = $this->attributeRepository->getAllowedValuesVariants($attribute);
                    if ($value instanceof Collection) {
                        /** @var AttributeAllowedValue $allowedValue */
                        foreach ($value as $allowedValue) {
                            $variantData['value'][$allowedValue->value] = $allowedValue->id;
                        }
                    }

                    break;
                case Attribute::TYPE_LIST:
                    $variantData['variants'] = $this->attributeRepository->getAllowedValuesVariants($attribute, true);

                    if ($value instanceof AttributeAllowedValue) {
                        $variantData['value'] = $value->id;
                    }
                    break;
                default:
                    $variantData['value'] = $value;
            }

            $variants[$attributeId] = $variantData;
        }

        return $variants;
    }

    /**
     * @param CatalogProduct $product
     * @return array
     */
    private function getDisabledAttributeVariants(CatalogProduct $product)
    {
        $attributes = $this->attributeRepository->getDisabledAttributesFor($product);

        return $this->attributesVariants($attributes);
    }

    /**
     * @param $oldData
     * @return array
     */
    private function getOldAttributeVariants($oldData)
    {
        $additionalAttributesData = array_get($oldData, 'additional_attributes', []);

        $additionalAttributesData = array_filter(
            $additionalAttributesData,
            function ($v) {
                if (isset($v['value'])) {
                    return !empty($v['value']);
                }

                if (isset($v['allowed_value_id'])) {
                    return !empty($v['allowed_value_id']);
                }

                if (isset($v['allowed_value_id_list'])) {
                    return count(array_get($v, 'allowed_value_id_list', [])) > 0;
                }

                return false;
            }
        );

        $attributeIds = array_keys($additionalAttributesData);
        $attributes = $this->attributeRepository->allIn($attributeIds);

        return $this->attributesVariants($attributes);
    }

    /**
     * @param Attribute[] $attributes
     * @return array
     */
    private function attributesVariants($attributes = [])
    {
        $variants = [];

        foreach ($attributes as $attribute) {
            $variantData = ['attribute' => $attribute];

            switch ($attribute->type) {
                case Attribute::TYPE_MULTIPLE_VALUES:
                    $variantData['variants'] = $this->attributeRepository->getAllowedValuesVariants($attribute);
                    break;

                case Attribute::TYPE_LIST:
                    $variantData['variants'] = $this->attributeRepository->getAllowedValuesVariants($attribute, true);
                    break;
                default:
            }

            $variants[$attribute->id] = $variantData;
        }

        return $variants;
    }
}
