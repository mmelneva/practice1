<?php namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Services\DataProviders\CatalogProduct\AttributesOutputDataProvider;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;
use App\Models\HomePage;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;

class HomePagesController extends BaseController
{
    const DEFAULT_PAGE_NAME = 'Главная';

    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /**
     * @var AttributesOutputDataProvider
     */
    private $attributesOutputDataProvider;

    /**
     * @var ProductTypePageRepositoryInterface
     */
    private $productTypePageRepository;

    /**
     * @var ReviewsRepositoryInterface
     */
    private $reviewsRepository;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param AttributesOutputDataProvider $attributesOutputDataProvider
     * @param ProductTypePageRepositoryInterface $productTypePageRepository
     * @param ReviewsRepositoryInterface $reviewsRepository
     */
    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository,
        AttributesOutputDataProvider $attributesOutputDataProvider,
        ProductTypePageRepositoryInterface $productTypePageRepository,
        ReviewsRepositoryInterface $reviewsRepository
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->attributesOutputDataProvider = $attributesOutputDataProvider;
        $this->productTypePageRepository = $productTypePageRepository;
        $this->reviewsRepository = $reviewsRepository;
    }

    public function getPage()
    {
        $node = $this->nodeRepository->findByType(StructureTypesServiceProvider::TYPE_HOME_PAGE, true);
        if (!is_null($node)) {
            /** @var HomePage $homePage */
            $homePage = \TypeContainer::getContentModelFor($node);
            $metaData = \MetaHelper::metaForObject($homePage, $node->name);
            $hideRegionsInPage = $node->hide_regions_in_page;
        } else {
            $homePage = null;
            $metaData = \MetaHelper::metaForName('Главная');
            $hideRegionsInPage = false;
        }

        $lastProducts =  $this->catalogProductRepository->getLastPublishedProducts();
        $popularProductTypePages = $this->productTypePageRepository->getPopularOnHomePage();
        $reviews = $this->reviewsRepository->getPublishedForHomePage();
        $products = $this->catalogProductRepository->getAllByIds($reviews->lists('product_id'));

        return \View::make('welcome')
            ->with(compact('homePage', 'lastProducts', 'popularProductTypePages', 'reviews', 'products'))
            ->with('popularInColumn', ceil(count($popularProductTypePages) / 3))
            ->with('page_type', 'homepage')
            ->with('hideRegionsInPage', $hideRegionsInPage)
            ->with($metaData);
    }
}
