<?php namespace App\Services\Repositories\AttributeValue;

use App\Models\AttributeValue;

interface AttributeValueRepositoryInterface
{
    public function newInstance(array $attributes = array(), $exists = false);
    public function createOrUpdate($attributeId, $productId, array $attributeValueData = []);
    public function delete($attributeId, $productId);
    public function getValuesForProducts($productList, $attributeList);
    public function getValuesForProduct($productId, $attributeList);

    public function getValueVariant(AttributeValue $attributeValue);
}
