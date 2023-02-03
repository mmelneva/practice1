<?php namespace App\Services\Repositories\AttributeAllowedValue;

interface AttributeAllowedValueRepositoryInterface
{
    public function createOrUpdate($attributeId, $allowedValueId, array $allowedValueData = []);
    public function deleteNotIn($attributeId, array $allowedValueIdList);
    public function newInstance(array $attributes = array(), $exists = false);
    public function getAllowedValueBy($attributeName, $attributeValue);

    /**
     * Get all attributes by ids
     * @param array $ids
     * @return mixed
     */
    public function allWithIds(array $ids);
}
