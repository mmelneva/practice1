<?php namespace App\Services\FormProcessors\CatalogProductsSubProcessor;

use App\Models\Attribute;
use App\Models\CatalogProduct;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\AttributeValue\AttributeValueRepositoryInterface;

/**
 * Class AdditionalAttributesSubProcessor
 * @package  App\Services\FormProcessors\CatalogProductsSubProcessor
 */
class AdditionalAttributesSubProcessor implements CatalogProductSubProcessorInterface
{
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
    }

    /**
     * @inheritdoc
     */
    public function prepareInputData($inputData)
    {
        $attributeValueDataList = array_get($inputData, 'additional_attributes', []);

        foreach ($attributeValueDataList as $attributeId => $attributeValueData) {
            if (isset($attributeValueData['value']) && isset($attributeValueData['is_number_value'])) {
                $attrValue = preg_replace('/(,\s|,|\.\s)/', '.', $attributeValueData['value']);
                array_set($inputData, "additional_attributes.{$attributeId}.value", trim($attrValue));
            }
        }

        return $inputData;
    }

    /**
     * @inheritdoc
     */
    public function process(CatalogProduct $catalogProduct, $formData)
    {
        $attributeValueDataList = array_get($formData, 'additional_attributes', []);
        $productId = $catalogProduct->id;

        foreach ($attributeValueDataList as $attributeId => $attributeValueData) {
            /** @var Attribute $attribute */
            $attribute = $this->attributeRepository->findById($attributeId);
            if (!is_null($attribute)) {
                switch ($attribute->type) {
                    case Attribute::TYPE_LIST:
                        $allowedValueId = array_get($attributeValueData, 'allowed_value_id');

                        if ($allowedValueId !== '0') {
                            $this->attributeValueRepository->createOrUpdate(
                                $attributeId,
                                $productId,
                                $attributeValueData
                            );
                        } else {
                            $this->attributeValueRepository->delete($attributeId, $productId);
                        }
                        break;
                    case Attribute::TYPE_MULTIPLE_VALUES:

                        $allowedValueIdList = array_get($attributeValueData, 'allowed_value_id_list', []);

                        if (count($allowedValueIdList) > 0) {
                            $this->attributeValueRepository->createOrUpdate(
                                $attributeId,
                                $productId,
                                $attributeValueData
                            );
                        } else {
                            $this->attributeValueRepository->delete($attributeId, $productId);
                        }

                        break;
                    default:
                        $value = array_get($attributeValueData, 'value');
                        if (!empty($value)) {
                            $this->attributeValueRepository->createOrUpdate(
                                $attributeId,
                                $productId,
                                $attributeValueData
                            );
                        } else {
                            $this->attributeValueRepository->delete($attributeId, $productId);
                        }
                }

            }
        }

    }
}
