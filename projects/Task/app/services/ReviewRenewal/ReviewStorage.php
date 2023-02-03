<?php namespace App\Services\ReviewRenewal;

use Carbon\Carbon;

/**
 * Interface ReviewStorage
 * Storage to access reviews.
 * @package App\Services\ReviewRenewal
 */
interface ReviewStorage
{
    /**
     * Get renewal review borders.
     *
     * @return ReviewWrapper[]
     */
    public function getRenewalReviewBorders();


    /**
     * Get last iteration.
     *
     * @return int
     */
    public function getLastIteration();


    /**
     * All renewal reviews.
     *
     * @return ReviewWrapper[]
     */
    public function allRenewal();


    /**
     * Update review and log info about it.
     *
     * @param ReviewWrapper $reviewWrapper
     * @param Carbon $newDate
     * @param $iteration
     */
    public function update(ReviewWrapper $reviewWrapper, Carbon $newDate, $iteration);
}
