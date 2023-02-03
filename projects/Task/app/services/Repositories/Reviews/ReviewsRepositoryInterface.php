<?php namespace App\Services\Repositories\Reviews;

use App\Models\Reviews;
use App\Models\CatalogCategory;
use App\Models\ProductTypePage;
use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Repositories\PaginateListRepositoryInterface;

/**
 * Interface ReviewsRepositoryInterface
 * @package App\Services\Repositories\Reviews
 */
interface ReviewsRepositoryInterface extends PaginateListRepositoryInterface, CreateUpdateRepositoryInterface
{
    public function allPublishedByPage($page, $limit);

    /**
     * Get published reviews for home page
     *
     * @return mixed
     */
    public function getPublishedForHomePage();

    /**
     * Get categories id associated with review
     *
     * @param Reviews $review
     * @return mixed
     */
    public function getCatalogCategoryIdListForAssociatedReview(Reviews $review);

    /**
     * Get ProductTypePages id associated with review
     *
     * @param Reviews $review
     * @return mixed
     */
    public function getProductTypePagesIdListForAssociatedReview(Reviews $review);


    /**
     * Get published order limited reviews for productTypePage
     *
     * @param ProductTypePage $productTypePage
     * @return mixed
     */
    public function getPublishedForProductTypePage(ProductTypePage $productTypePage);

    /**
     * Get published order limited reviews for CatalogCategory
     *
     * @param CatalogCategory $category
     * @return mixed
     */
    public function getPublishedForCatalogCategory(CatalogCategory $category);

    /**
     * Get first and last reviews which are renewal.
     *
     * @return mixed
     */
    public function getRenewalReviewBorders();

    /**
     * Get all renewal reviews.
     *
     * @return mixed
     */
    public function allRenewal();
}
