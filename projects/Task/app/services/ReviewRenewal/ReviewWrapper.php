<?php namespace App\Services\ReviewRenewal;

use Carbon\Carbon;

/**
 * Class ReviewWrapper
 * @package App\Services\ReviewRenewal
 */
class ReviewWrapper
{
    private $review;
    private $date;

    public function __construct($review, Carbon $date)
    {
        $this->review = $review;
        $this->date = $date;
    }


    /**
     * Get review.
     *
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }


    /**
     * Get date.
     *
     * @return Carbon
     */
    public function getDate()
    {
        return $this->date;
    }
}
