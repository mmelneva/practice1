<?php namespace App\Services\ReviewRenewal;

use Carbon\Carbon;

class ReviewRenewal
{
    private $reviewStorage;
    private $reportSender;
    private $secondsLimit;

    public function __construct(
        ReviewStorage $reviewStorage,
        ReportSender $reportSender,
        $daysLimit = 60
    ) {
        $this->reviewStorage = $reviewStorage;
        $this->reportSender = $reportSender;
        $this->secondsLimit = $daysLimit * 24 * 60 * 60;
    }


    /**
     * Renew all the reviews which are allowed to renew.
     */
    public function renewAll()
    {
        $reviewBorders = $this->reviewStorage->getRenewalReviewBorders();
        $firstReview = $reviewBorders['first'];
        $lastReview = $reviewBorders['last'];
        if (!$this->allowedToProcess($firstReview, $lastReview)) {
            return;
        }

        $iteration = $this->reviewStorage->getLastIteration();
        $iteration += 1;

        $diffFactor = $this->getDiffFactor($firstReview->getDate(), $lastReview->getDate());
        $reviewList = $this->reviewStorage->allRenewal();
        foreach ($reviewList as $review) {
            $newReviewDate = $this->getNewReviewDate($review->getDate(), $firstReview->getDate(), $diffFactor);
            if (is_null($newReviewDate)) {
                continue;
            }
            $this->reviewStorage->update($review, $newReviewDate, $iteration);
        }

        $this->reportSender->report($iteration);
    }


    /**
     * Get new review date.
     *
     * @param Carbon $reviewDate
     * @param Carbon $startDate
     * @param $diffFactor
     * @return Carbon|null
     */
    private function getNewReviewDate(Carbon $reviewDate, Carbon $startDate, $diffFactor)
    {
        if ($reviewDate->eq($startDate)) {
            return null;
        }
        $diff = $startDate->diffInSeconds($reviewDate, false);
        $newDiff = $diff * $diffFactor;
        $newReviewDate = $startDate->copy()->addSeconds($newDiff);

        return $newReviewDate;
    }


    /**
     * Get diff factor.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    private function getDiffFactor(Carbon $startDate, Carbon $endDate)
    {
        $newEndDate = $endDate->copy();
        do {
            $newEndDate = $newEndDate->addSeconds($this->secondsLimit);
        } while ($newEndDate->diffInSeconds(Carbon::now(), false) > $this->secondsLimit);

        $originalDiff = $startDate->diffInSeconds($endDate, false);
        $newDiff = $startDate->diffInSeconds($newEndDate, false);
        $diffFactor = $newDiff / $originalDiff;

        return $diffFactor;
    }


    /**
     * Check if allowed to process.
     *
     * @param ReviewWrapper|null $firstReview
     * @param ReviewWrapper|null $lastReview
     * @return bool
     */
    private function allowedToProcess(ReviewWrapper $firstReview = null, ReviewWrapper $lastReview = null)
    {
        // if there are no reviews
        if (is_null($firstReview) || is_null($lastReview)) {
            return false;
        }

        // if there are only one review
        if ($firstReview->getDate()->eq($lastReview->getDate())) {
            return false;
        }

        // no enough time has passed
        $diff = $lastReview->getDate()->diffInSeconds(Carbon::now(), false);
        if ($diff < $this->secondsLimit) {
            return false;
        }

        return true;
    }
}
