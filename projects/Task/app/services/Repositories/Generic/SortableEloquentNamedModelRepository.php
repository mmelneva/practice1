<?php namespace App\Services\Repositories\Generic;

use App\Services\Repositories\SortableListRepositoryInterface;

/**
 * Class SortableEloquentNamedModelRepository
 * @package App\Services\Repositories\Generic
 */
abstract class SortableEloquentNamedModelRepository extends EloquentNamedModelRepository implements
    SortableListRepositoryInterface
{
    const POSITION_STEP = 10;

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->modelInstance->orderBy('position')->get();
    }

    public function allByIds(array $ids)
    {
        if (count($ids) === 0) {
            $ids = [null];
        }

        return $this->modelInstance->orderBy('position')->whereIn('id', $ids)->get();
    }


    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        if (empty($data['position'])) {
            $maxPosition = $this->modelInstance->max('position');
            if (is_null($maxPosition)) {
                $maxPosition = 0;
            }

            $data['position'] = $maxPosition + self::POSITION_STEP;
        }

        return $this->modelInstance->create($data);
    }

    /**
     * @inheritDoc
     */
    public function updatePositions(array $positions)
    {
        foreach ($positions as $id => $positionNumber) {
            /**
             * @var \Eloquent $model
             */
            $model = $this->modelInstance->find($id);
            if (!is_null($model)) {
                $model->update(['position' => $positionNumber * self::POSITION_STEP]);
            }
        }
    }
}
