<?php namespace App\Services\DataProviders\ClientProductList\Pagination;

use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;

class ReviewsPaginationDataProvider implements PaginationDataProviderInterface
{
    /**
     * @var ReviewsRepositoryInterface
     */
    private $reviewsRepository;

    /**
     * @param ReviewsRepositoryInterface $reviewsRepository
     */
    public function __construct(
        ReviewsRepositoryInterface $reviewsRepository
    ) {
        $this->reviewsRepository = $reviewsRepository;
    }

    public function getPaginationStructure($elementsOnPage, $page)
    {
        return $this->reviewsRepository->allPublishedByPage(
            $page,
            $elementsOnPage
        );
    }
}
