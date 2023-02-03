<?php namespace App\Models\Features;

/**
 * Class DeleteHelpers
 * Trait with delete helpers.
 *
 * @package App\SocioCompass\Models\Features
 */
trait DeleteHelpers
{
    /**
     * Delete all related models for relation.
     *
     * @param $associationQueryBuilder
     */
    protected static function deleteRelatedAll($associationQueryBuilder)
    {
        foreach ($associationQueryBuilder->get() as $subModel) {
            $subModel->delete();
        }
    }

    /**
     * Delete first related model for relation.
     *
     * @param $associationQueryBuilder
     */
    protected static function deleteRelatedFirst($associationQueryBuilder)
    {
        $model = $associationQueryBuilder->first();
        if (!is_null($model)) {
            $model->delete();
        }
    }
}
