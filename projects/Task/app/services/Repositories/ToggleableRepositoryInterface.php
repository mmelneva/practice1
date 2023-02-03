<?php namespace App\Services\Repositories;

interface ToggleableRepositoryInterface
{
    /**
     * Toggle an attribute.
     *
     * @param $id
     * @param $attribute
     * @return \Eloquent|null
     */
    public function toggleAttribute($id, $attribute);
}
