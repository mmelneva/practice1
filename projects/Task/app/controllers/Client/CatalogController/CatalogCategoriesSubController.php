<?php namespace App\Controllers\Client\CatalogController;

use App\Models\CatalogCategory;
use App\Services\DataProviders\CatalogProduct\AttributesOutputDataProvider;
use App\Services\DataProviders\ClientProductList\CatalogProductListDataProvider;
use App\Services\DataProviders\ClientProductList\Filter\CatalogFilterDataProvider;
use App\Services\DataProviders\ClientProductList\Filter\DefaultFilterDataProvider;
use App\Services\DataProviders\ClientProductList\PageUrl\CatalogPageUrlDataProvider;
use App\Services\DataProviders\ClientProductList\PageUrl\CategoryPageUrlDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\FilteredCatalogPaginationDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\FilteredPaginationDataProvider;
use App\Services\DataProviders\ClientProductList\ProductListDataProvider;
use App\Services\DataProviders\ClientProductList\ProductListInfo\NoAdditionalInfo;
use App\Services\DataProviders\ClientProductList\Sorting\DefaultSortingDataProvider;
use App\Services\Pagination\PaginationFactory;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;

class CatalogCategoriesSubController extends CatalogSubController
{
    const DEFAULT_PAGE_NAME = 'Каталог';

    private $catalogProductRepository;
    private $attributesOutputDataProvider;
    private $productTypePageRepository;

    /**
     * @var ReviewsRepositoryInterface
     */
    private $reviewsRepository;

    public function __construct(
        CatalogProductRepositoryInterface $catalogProductRepository,
        AttributesOutputDataProvider $attributesOutputDataProvider,
        ProductTypePageRepositoryInterface $productTypePageRepository,
        ReviewsRepositoryInterface $reviewsRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        NodeRepositoryInterface $nodeRepository
    ) {
        parent::__construct($nodeRepository, $catalogCategoryRepository);
        $this->catalogProductRepository = $catalogProductRepository;
        $this->attributesOutputDataProvider = $attributesOutputDataProvider;
        $this->productTypePageRepository = $productTypePageRepository;
        $this->reviewsRepository = $reviewsRepository;
        $this->nodeRepository = $nodeRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
    }

    public function getCatalogCategoryResponse(CatalogCategory $category, $page)
    {
        $sort = \Input::get('sort');
        $filterData = \Input::get('filter', []);
        if (!is_array($filterData)) {
            \App::abort(404, 'Incorrect filter data');
        }

        $productListDataProvider = new ProductListDataProvider(
            $this->attributesOutputDataProvider,
            $category,
            new FilteredPaginationDataProvider($this->catalogProductRepository, $category, $filterData, $sort),
            new NoAdditionalInfo(),
            new DefaultSortingDataProvider($this->catalogProductRepository, $sort),
            new DefaultFilterDataProvider($this->catalogProductRepository, $category, $filterData),
            new CategoryPageUrlDataProvider($this->catalogProductRepository, $category, $filterData, $sort),
            new PaginationFactory()
        );

        $productListData = $productListDataProvider->getProductListData($page);

        $metaData = \MetaHelper::metaForObject($category);
        $metaData = \MetaHelper::appendPagination($metaData, $productListData['paginationData']);
        $breadcrumbs = $this->getBreadcrumbs($category);
        $rootCategory = \CatalogPathFinder::getCategoryRoot($category);

        $reviews = $this->reviewsRepository->getPublishedForCatalogCategory($category);
        $products = $this->catalogProductRepository->getAllByIds($reviews->lists('product_id'));

        return \View::make('client.catalog_categories.category')
            ->with('category', $category)
            ->with('rootCategory', $rootCategory)
            ->with('productListData', $productListData)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('page_type', 'categorypage')
            ->with('sidebar', $category->content_for_sidebar)
            ->with('show_small_content', true)
            ->with('type_order_button', $category->type_order_button)
            ->with(compact('reviews', 'products'))
            ->with($metaData);
    }

    public function getCatalogResponse($page)
    {
        $sort = \Input::get('sort');
        $filterData = \Input::get('filter', []);
        if (!is_array($filterData)) {
            \App::abort(404, 'Incorrect filter data');
        }

        $productListDataProvider = new CatalogProductListDataProvider(
            $this->attributesOutputDataProvider,
            new FilteredCatalogPaginationDataProvider($this->catalogProductRepository, $filterData, $sort),
            new NoAdditionalInfo(),
            new DefaultSortingDataProvider($this->catalogProductRepository, $sort),
            new CatalogFilterDataProvider($this->catalogProductRepository, $filterData),
            new CatalogPageUrlDataProvider($this->catalogProductRepository, $filterData, $sort),
            new PaginationFactory()
        );

        $productListData = $productListDataProvider->getProductListData($page);

        $breadcrumbs = \Breadcrumbs::init();
        $metaData = \MetaHelper::metaForName(self::DEFAULT_PAGE_NAME);
        $metaData = \MetaHelper::appendPagination($metaData, $productListData['paginationData']);
        $breadcrumbs->add(self::DEFAULT_PAGE_NAME);

        return \View::make('client.catalog_categories.index')
            ->with('productListData', $productListData)
            ->with('breadcrumbs', $breadcrumbs)
            ->with($metaData);
    }
}
