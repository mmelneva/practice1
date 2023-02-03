<?php namespace App\Services\Repositories\ReviewsDateChange;

use App\Models\Reviews;
use Carbon\Carbon;

interface ReviewsDateChangeRepositoryInterface
{
    /**
     * Get last iteration number of review date change.
     *
     * @return mixed
     */
    public function getLastIteration();


    /**
     * Create change note.
     *
     * @param Reviews $review
     * @param $iteration
     * @param Carbon $oldValue
     * @param Carbon $newValue
     * @return mixed
     */
    public function create(Reviews $review, $iteration, Carbon $oldValue, Carbon $newValue);


    /**
     * Count changes within iteration.
     *
     * @param $iteration
     * @return mixed
     */
    public function countWithinIteration($iteration);


    /**
     * All the changes within iteration.
     *
     * @param $iteration
     * @return mixed
     */
    public function allWithinIteration($iteration);
}
