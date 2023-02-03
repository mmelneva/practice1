<?php namespace App\Services\DataProviders\Attribute;

use App\Models\Attribute;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\AttributeAllowedValue\AttributeAllowedValueRepositoryInterface;
use App\Services\Repositories\AttributeValue\AttributeValueRepositoryInterface;

/**
 * Class AllowedValuesDataProvider
 * @package  App\Services\DataProviders\Attribute
 */
class AllowedValuesDataProvider
{
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository,
        AttributeAllowedValueRepositoryInterface $attributeAllowedValueRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->attributeAllowedValueRepository = $attributeAllowedValueRepository;
    }

    public function getAllowedValuesFormData(Attribute $attribute, array $oldData = [])
    {
        $allowedValuesData = [];
        foreach (array_get($oldData, 'allowed_values', []) as $key => $allowedValueData) {
            $allowedValuesData[$key] = $this->attributeAllowedValueRepository->newInstance($allowedValueData);
        }

        array_set($oldData, 'allowed_values', $allowedValuesData);

        if (count($allowedValuesData) == 0) {
            $allowedValuesData = $this->attributeRepository->getAllowedValues($attribute);
        }

        return ['allowed_values' => $allowedValuesData];
    }
}
