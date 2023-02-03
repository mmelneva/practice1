<?php namespace App\Services\RepositoryFeatures\Attribute;

/**
 * Class EloquentAttributeToggler
 * Helper to toggle eloquent model attributes.
 *
 * @package App\Services\Repositories\RepositoryHelpers
 */
class EloquentAttributeToggler
{
    /**
     * Toggle attribute in model and save it.
     *
     * @param \Eloquent $model
     * @param $attribute
     * @return null
     */
    public function toggleAttribute($model, $attribute)
    {
        if (is_null($model)) {
            return null;
        } else {
            $model->{$attribute} = !$model->{$attribute};
            $model->save();
            return $model;
        }
    }
}
