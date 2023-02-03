<?php namespace App\Services\DataProviders\Reviews;


use App\Models\Reviews;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;

/**
 * Class AssociatedCategoriesReviewDataProvider
 * @package App\Services\DataProviders\Reviews
 */
class AssociatedCategoriesReviewDataProvider
{
    public function __construct(ReviewsRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAssociatedCategoriesFormData(Reviews $review)
    {
        $associatedCategoriesIds = $this->reviewRepository->getCatalogCategoryIdListForAssociatedReview($review);

        return [
            'attached_associated_categories' => $associatedCategoriesIds,
        ];
    }
}
