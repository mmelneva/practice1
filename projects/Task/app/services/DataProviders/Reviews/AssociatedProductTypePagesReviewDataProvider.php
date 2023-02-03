<?php namespace App\Services\DataProviders\Reviews;


use App\Models\Reviews;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;

/**
 * Class AssociatedProductTypePagesReviewDataProvider
 * @package App\Services\DataProviders\Reviews
 */
class AssociatedProductTypePagesReviewDataProvider
{
    public function __construct(ReviewsRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAssociatedCategoriesFormData(Reviews $review)
    {
        $associatedProductTypePagesIds = $this->reviewRepository->getProductTypePagesIdListForAssociatedReview($review);

        return [
            'attached_associated_product_type_pages' => $associatedProductTypePagesIds,
        ];
    }
}
