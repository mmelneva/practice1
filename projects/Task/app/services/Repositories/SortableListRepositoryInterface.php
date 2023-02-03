<?php namespace App\Services\Repositories;

/**
 * Interface SortableListRepositoryInterface
 * @package App\Services\Repositories
 */
interface SortableListRepositoryInterface extends ListRepositoryInterface
{
    /**
     * Mass update positions.
     * Needs array: [id => position]
     *
     * @param array $positions
     * @return mixed
     */
    public function updatePositions(array $positions);
}
