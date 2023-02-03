<?php namespace App\Services\ReviewRenewal\Adapter;

use App\Services\Repositories\ReviewsDateChange\ReviewsDateChangeRepositoryInterface;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;
use App\Services\ReviewRenewal\ReviewStorage;
use App\Services\ReviewRenewal\ReviewWrapper;
use Carbon\Carbon;

class ReviewStorageAdapter implements ReviewStorage
{
    private $reviewRepository;
    private $dateChangeRepository;

    public function __construct(
        ReviewsRepositoryInterface $reviewRepository,
        ReviewsDateChangeRepositoryInterface $dateChangeRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->dateChangeRepository = $dateChangeRepository;
    }


    public function getRenewalReviewBorders()
    {
        $reviewBorders = $this->reviewRepository->getRenewalReviewBorders();
        $firstReview = $reviewBorders['first'];
        $lastReview = $reviewBorders['last'];

        if (!is_null($firstReview)) {
            $firstReviewWrapper = new ReviewWrapper($firstReview, $firstReview->date_at);
        } else {
            $firstReviewWrapper = null;
        }

        if (!is_null($lastReview)) {
            $lastReviewWrapper = new ReviewWrapper($lastReview, $lastReview->date_at);
        } else {
            $lastReviewWrapper = null;
        }

        return [
            'first' => $firstReviewWrapper,
            'last' => $lastReviewWrapper,
        ];
    }


    public function getLastIteration()
    {
        return $this->dateChangeRepository->getLastIteration();
    }


    public function allRenewal()
    {
        $reviewWrapperList = [];
        $reviewList = $this->reviewRepository->allRenewal();
        foreach ($reviewList as $review) {
            $reviewWrapperList[] = new ReviewWrapper($review, $review->date_at);
        }

        return $reviewWrapperList;
    }


    public function update(ReviewWrapper $reviewWrapper, Carbon $newDate, $iteration)
    {
        $this->reviewRepository->update($reviewWrapper->getReview()->id, ['date_at' => $newDate]);
        $this->dateChangeRepository->create(
            $reviewWrapper->getReview(),
            $iteration,
            $reviewWrapper->getDate(),
            $newDate
        );
    }
}
