<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Services\DataProviders\ClientProductList\PageUrl\SimplePageUrlDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\ReviewsPaginationDataProvider;
use App\Services\FormProcessors\ReviewsFormProcessor\ClientReviewsFormProcessor;
use App\Services\Pagination\PaginationFactory;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;

class ReviewsPagesController extends BaseController
{
    const ELEMENTS_ON_PAGE = 10;
    const META_TEMPLATE_KEY = 'reviews_page';

    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var ReviewsRepositoryInterface
     */
    private $reviewsRepository;

    /**
     * @var ReviewsPaginationDataProvider
     */
    private $paginationDataProvider;

    /**
     * @var PaginationFactory
     */
    private $paginationFactory;

    /**
     * @var SimplePageUrlDataProvider
     */
    private $pageUrlDataProvider;

    /**
     * @var ClientReviewsFormProcessor
     */
    private $formProcessor;

    private $productRepository;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     * @param ReviewsRepositoryInterface $reviewsRepository
     * @param ReviewsPaginationDataProvider $paginationDataProvider
     * @param PaginationFactory $paginationFactory
     * @param SimplePageUrlDataProvider $pageUrlDataProvider
     * @param ClientReviewsFormProcessor $formProcessor
     */
    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        ReviewsRepositoryInterface $reviewsRepository,
        ReviewsPaginationDataProvider $paginationDataProvider,
        PaginationFactory $paginationFactory,
        SimplePageUrlDataProvider $pageUrlDataProvider,
        ClientReviewsFormProcessor $formProcessor,
        CatalogProductRepositoryInterface $catalogProductRepository
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->reviewsRepository = $reviewsRepository;
        $this->paginationDataProvider = $paginationDataProvider;
        $this->paginationFactory = $paginationFactory;
        $this->pageUrlDataProvider = $pageUrlDataProvider;
        $this->formProcessor = $formProcessor;
        $this->productRepository = $catalogProductRepository;
    }

    public function getPage($pageData = null)
    {
        $node = $this->nodeRepository->findByType(StructureTypesServiceProvider::TYPE_REVIEWS_PAGE, true);

        if (is_null($node)) {
            \App::abort(404, 'Page is not found');
        }

        $page = $this->getPageNumber($pageData);
        if (!is_null($pageData) && (is_null($page) || $page == 1)) {
            \App::abort(404, 'Page ' . $pageData . ' is not allowed in url');
        } elseif(is_null($page)) {
            $page = 1;
        }

        $reviewsPage = \TypeContainer::getContentModelFor($node);

        $breadcrumbs = \Breadcrumbs::init();
        $breadcrumbs->add($node->name);
        $metaData = \MetaHelper::metaForObject($reviewsPage, $node->name);

        $paginationStructure = $this->paginationDataProvider->getPaginationStructure(self::ELEMENTS_ON_PAGE, $page);

        $paginationData = $this->paginationFactory->getPagination(
            function ($page) {
                return $this->pageUrlDataProvider->getPageUrlByBaseUrl(route('reviews'), $page);
            },
            $paginationStructure['items'],
            $paginationStructure['total'],
            $paginationStructure['page'],
            $paginationStructure['limit']
        );

        $products = $this->productRepository->getAllByIds($paginationData->getItems()->lists('product_id'));

        return \View::make('client.reviews_pages.page')
            ->with(compact('reviewsPage', 'breadcrumbs', 'paginationData', 'products'))
            ->with($metaData);
    }

    private function getPageNumber($pageData = null)
    {
        if (!is_null($pageData) && preg_match('/^page-([1-9]\d*)$/', $pageData, $matches)) {
            $page = $matches[1];
        } else {
            $page = null;
        }

        return $page;
    }

    public function postStore()
    {
        $status = 'ERROR';
        $content = '';
        if (\Request::ajax()) {
            $inputData = \Input::all();

            /** @var \App\Models\Reviews $created */
            $created = $this->formProcessor->create($inputData);

            if (!is_null($created)) {
                $status = 'OK';
                $content = '<div class="success">Спасибо, Ваш отзыв принят.</div>';
            } else {
                $errorsArray = [];
                $errors = $this->formProcessor->errors();
                foreach ($errors as $error) {
                    $errorsArray = array_merge($errorsArray, $error);
                }
                if (count($errorsArray) > 0) {
                    $content = '<div class="errors">' . implode('<br>', $errorsArray) . '</div>';
                }
            }
        }

        return \Response::json(compact('status', 'content'));
    }
}
