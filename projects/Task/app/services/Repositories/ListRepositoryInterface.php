<?php namespace App\Services\Repositories;

interface ListRepositoryInterface
{
    /**
     * Get all models.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get all models by list of ids.
     *
     * @param array $ids
     * @return mixed
     */
    public function allByIds(array $ids);
    
    /**
     * Get new instance of model.
     *
     * @param array $data
     * @param boolean $exists
     * @return array
     */
    public function newInstance(array $data = [], $exists = false);

    /**
     * Find model by id.
     *
     * @param int $id
     * @return \Eloquent
     */
    public function findById($id);

    /**
     * Delete the model by id.
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * Get array of variants (id => name)
     *
     * @param bool $nullVariant - need or not null variant.
     *
     * @return array
     */
    public function getVariants($nullVariant = false);
}
