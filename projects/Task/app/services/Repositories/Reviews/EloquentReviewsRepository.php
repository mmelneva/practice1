<?php namespace App\Services\Repositories\Reviews;

use App\Models\Reviews;
use App\Models\CatalogCategory;
use App\Models\ProductTypePage;
use App\Services\Repositories\Generic\EloquentNamedModelRepository;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentReviewsRepository
 * @package  App\Services\Repositories\Reviews
 */
class EloquentReviewsRepository extends EloquentNamedModelRepository implements ReviewsRepositoryInterface
{
    const HOME_PAGE_LIMIT = 24;
    const PRODUCT_TYPE_PAGE_LIMIT = 10;
    const CATALOG_CATEGORY_LIMIT = 10;

    public function __construct(EloquentAttributeToggler $attributeToggler, PossibleVariants $possibleVariants)
    {
        parent::__construct(new Reviews, $attributeToggler, $possibleVariants);
    }

    protected function scopeOrdered($query)
    {
        return $query->orderBy('date_at', 'DESC');
    }

    public function byPage($page = 1, $limit = 20)
    {
        $query = $this->modelInstance->query();
        $this->scopeOrdered($query);

        $orderList = $query
            ->skip($limit * ($page - 1))
            ->take($limit)->get();

        $total = $this->modelInstance->count();

        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'items' => $orderList,
        ];
    }

    protected function scopePublished($query)
    {
        return $query->where('publish', true);
    }

    public function allPublishedByPage($page, $limit)
    {
        $query = $this->modelInstance->query();
        $this->scopePublished($query);
        $this->scopeOrdered($query);

        $countQuery = clone $query;

        $total = $countQuery->count();
        $items = $query->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        return [
            'page' => $page,
            'limit' => $limit,
            'items' => $items,
            'total' => $total,
        ];
    }

    public function getPublishedForHomePage()
    {
        $query = $this->modelInstance->where('on_home_page', true);
        $this->scopePublished($query);
        $this->scopeOrdered($query);

        return $query->limit(self::HOME_PAGE_LIMIT)->get();
    }

    public function getPublishedForProductTypePage(ProductTypePage $productTypePage)
    {
        if (!$productTypePage->use_reviews_associations) {
            return Collection::make([]);
        }
        $query = $productTypePage->reviews();
        $this->scopePublished($query);
        $this->scopeOrdered($query);

        return $query->limit(self::PRODUCT_TYPE_PAGE_LIMIT)->get();
    }

    public function getPublishedForCatalogCategory(CatalogCategory $category)
    {
        if (!$category->use_reviews_associations) {
            return Collection::make([]);
        }
        $query = $category->reviews();
        $this->scopePublished($query);
        $this->scopeOrdered($query);

        return $query->limit(self::CATALOG_CATEGORY_LIMIT)->get();
    }

    public function getCatalogCategoryIdListForAssociatedReview(Reviews $review)
    {
        return $review->associatedCategories->lists('id');
    }

    public function getProductTypePagesIdListForAssociatedReview(Reviews $review)
    {
        return $review->associatedProductTypePages->lists('id');
    }

    public function getRenewalReviewBorders()
    {
        $query = Reviews::query()->where('keep_review_date', false)->whereNotNull('date_at');
        $queryFirst = clone $query;
        $queryLast = clone $query;

        $firstReview = $queryFirst->orderBy('date_at', 'ASC')->first();
        $lastReview = $queryLast->orderBy('date_at', 'DESC')->first();

        if (!is_null($firstReview) && !is_null($lastReview) && $firstReview->id == $lastReview->id) {
            $lastReview = $firstReview;
        }

        return [
            'first' => $firstReview,
            'last' => $lastReview,
        ];
    }

    public function allRenewal()
    {
        return Reviews::query()->where('keep_review_date', false)->whereNotNull('date_at')
            ->get();
    }
}
