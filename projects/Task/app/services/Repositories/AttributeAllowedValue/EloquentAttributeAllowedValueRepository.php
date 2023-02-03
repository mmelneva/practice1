<?php namespace App\Services\Repositories\AttributeAllowedValue;

use App\Models\AttributeAllowedValue;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentAttributeAllowedValueRepository
 * @package  App\Services\Repositories\AttributeAllowedValue
 */
class EloquentAttributeAllowedValueRepository implements AttributeAllowedValueRepositoryInterface
{
    const POSITION_STEP = 10;

    protected $modelInstance;

    public function __construct()
    {
        $this->modelInstance = new AttributeAllowedValue;
    }

    /**
     * @inheritDoc
     */
    public function createOrUpdate($attributeId, $allowedValueId, array $allowedValueData = [])
    {
        if (empty($allowedValueData['position'])) {
            $maxPosition = $this->modelInstance->where('attribute_id', $attributeId)->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }
            $allowedValueData['position'] = $maxPosition + self::POSITION_STEP;
        }

        $existingAllowedValue = $this->modelInstance->where('attribute_id', $attributeId)
            ->where('id', $allowedValueId)->first();

        if (is_null($existingAllowedValue)) {
            /** @var AttributeAllowedValue $existingAllowedValue */
            $existingAllowedValue = $this->modelInstance->newInstance();
            $existingAllowedValue->attribute_id = $attributeId;
        }


        $existingAllowedValue->fill($allowedValueData);
        $existingAllowedValue->save();

        return $existingAllowedValue;
    }

    /**
     * @inheritDoc
     */
    public function deleteNotIn($attributeId, array $allowedValueIdList)
    {
        $query = $this->modelInstance->where('attribute_id', $attributeId);
        if (count($allowedValueIdList) > 0) {
            $query->whereNotIn('id', $allowedValueIdList);
        }
        $modelInstances = $query->get();
        /** @var AttributeAllowedValue $modelInstance */
        foreach ($modelInstances as $modelInstance) {
            $modelInstance->delete();
        }
    }

    /**
     * @inheritDoc
     */
    public function newInstance(array $attributes = array(), $exists = false)
    {
        return $this->modelInstance->newInstance($attributes, $exists);
    }

    /**
     * @inheritDoc
     */
    public function getAllowedValueBy($attributeName, $attributeValue)
    {
        static $allowedValues;

        if (is_null($allowedValues)) {
            foreach ($this->modelInstance->all() as $allowedValue) {
                $allowedValues[$allowedValue->attribute->name][$allowedValue->value] = $allowedValue;
            }
        }

        return array_get(array_get($allowedValues, $attributeName), $attributeValue);
    }

    public function allWithIds(array $ids)
    {
        if (count($ids) === 0) {
            return Collection::make([]);
        }

        return AttributeAllowedValue::whereIn('id', $ids)->orderBy('position')->get();
    }
}
