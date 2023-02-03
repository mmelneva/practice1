<?php namespace App\Services\Repositories\Generic;

use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\ListRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;

/**
 * Class EloquentNamedModelRepository
 * @package App\SocioCompass\Repositories\Generic
 */
abstract class EloquentNamedModelRepository implements ListRepositoryInterface, CreateUpdateRepositoryInterface, ToggleableRepositoryInterface
{
    /**
     * @var \Eloquent
     */
    protected $modelInstance;

    /**
     * @var EloquentAttributeToggler
     */
    protected $attributeToggler;

    /**
     * @var PossibleVariants
     */
    protected $possibleVariants;

    /**
     * @param \Eloquent $modelInstance
     * @param EloquentAttributeToggler $attributeToggler
     * @param PossibleVariants $possibleVariants
     */
    public function __construct(
        \Eloquent $modelInstance,
        EloquentAttributeToggler $attributeToggler,
        PossibleVariants $possibleVariants
    ) {
        $this->modelInstance = $modelInstance;
        $this->attributeToggler = $attributeToggler;
        $this->possibleVariants = $possibleVariants;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return $this->modelInstance->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data)
    {
        /**
         * @var \Eloquent $object
         */
        $object = $this->modelInstance->find($id);
        if (!is_null($object)) {
            $object->update($data);
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->modelInstance->orderBy('name')->get();
    }

    public function allByIds(array $ids)
    {
        if (count($ids) === 0) {
            $ids = [null];
        }

        return $this->modelInstance->orderBy('name')->whereIn('id', $ids)->get();
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
    public function findById($id)
    {
        /**
         * @var \Eloquent $object
         */
        return $this->modelInstance->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        /**
         * @var \Eloquent $object
         */
        $object = $this->modelInstance->find($id);
        if (!is_null($object)) {
            $object->delete();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function toggleAttribute($id, $attribute)
    {
        return $this->attributeToggler->toggleAttribute($this->findById($id), $attribute);
    }

    /**
     * @inheritDoc
     */
    public function getVariants($nullVariant = false)
    {
        return $this->possibleVariants->getVariants($this->all(), $nullVariant);
    }
}
