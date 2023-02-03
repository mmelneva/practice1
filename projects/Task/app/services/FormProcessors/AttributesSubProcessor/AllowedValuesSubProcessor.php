<?php namespace App\Services\FormProcessors\AttributesSubProcessor;

use App\Models\Attribute;
use App\Services\Repositories\AttributeAllowedValue\AttributeAllowedValueRepositoryInterface;

/**
 * Class AllowedValuesSubProcessor
 * @package  App\Services\FormProcessors\AttributesSubProcessor
 */
class AllowedValuesSubProcessor implements AttributeSubProcessorInterface
{
    private $attributeAllowedValueRepository;

    public function __construct(AttributeAllowedValueRepositoryInterface $attributeAllowedValueRepository)
    {
        $this->attributeAllowedValueRepository = $attributeAllowedValueRepository;
    }

    public function prepareInputData($inputData)
    {
        return $inputData;
    }

    public function process(Attribute $attribute, $formData)
    {
        $allowedValuesData = array_get($formData, 'allowed_values', []);

        $attributeIdList = [];

        foreach ($allowedValuesData as $allowedValueData) {
            $allowedValue = $this->attributeAllowedValueRepository->createOrUpdate(
                $attribute->id,
                array_get($allowedValueData, 'id'),
                $allowedValueData
            );

            $attributeIdList[] = $allowedValue->id;
        }

        $this->attributeAllowedValueRepository->deleteNotIn($attribute->id, $attributeIdList);
    }
}
