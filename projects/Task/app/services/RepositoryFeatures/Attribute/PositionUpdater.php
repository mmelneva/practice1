<?php namespace App\Services\RepositoryFeatures\Attribute;

/**
 * Class PositionUpdater
 * @package App\Services\RepositoryFeatures\Attribute
 */
class PositionUpdater
{
    /**
     * Update positions according to array.
     *
     * @param \Eloquent $modelTemplate
     * @param array $positionArray
     * @param $positionStep
     */
    public function updatePositions(\Eloquent $modelTemplate, array $positionArray, $positionStep)
    {
        foreach ($positionArray as $id => $positionNumber) {
            $model = $modelTemplate->find($id);
            if (!is_null($model)) {
                $model->position = $positionNumber * $positionStep;
                $model->save();
            }
        }
    }
}
