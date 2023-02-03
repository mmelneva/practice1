<?php namespace App\Controllers\Client\DynamicPageController;

use App\Models\Node;
use App\Models\ProductTypePage;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterUrl;
use App\Services\Catalog\FilterUrlParser\FilterUrlParser;
use App\Services\DataProviders\CatalogProduct\AttributesOutputDataProvider;
use App\Services\DataProviders\ClientProductList\CatalogProductListDataProvider;
use App\Services\DataProviders\ClientProductList\Filter\CatalogFilterDataProvider;
use App\Services\DataProviders\ClientProductList\Filter\DefaultFilterDataProvider;
use App\Services\DataProviders\ClientProductList\PageUrl\ProductTypePageUrlDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\EmptyPaginationDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\FilteredProductTypePaginationDataProvider;
use App\Services\DataProviders\ClientProductList\Pagination\ManualPaginationDataProvider;
use App\Services\DataProviders\ClientProductList\ProductListDataProvider;
use App\Services\DataProviders\ClientProductList\ProductListInfo\ProductTypePageAdditionalInfo;
use App\Services\DataProviders\ClientProductList\Sorting\DefaultSortingDataProvider;
use App\Services\Pagination\PaginationFactory;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;

class ProductTypePageSubController extends DynamicPageSubController
{
    private $catalogCategoryRepository;
    private $catalogProductRepository;
    private $prodTypePageAssociationRepo;
    private $attributesOutputDataProvider;
    private $filterUrlParser;

    /**
     * @var ReviewsRepositoryInterface
     */
    private $reviewsRepository;


    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo,
        AttributesOutputDataProvider $attributesOutputDataProvider,
        FilterUrlParser $filterUrlParser,
        ReviewsRepositoryInterface $reviewsRepository
    ) {
        parent::__construct($nodeRepository);
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->prodTypePageAssociationRepo = $prodTypePageAssociationRepo;
        $this->attributesOutputDataProvider = $attributesOutputDataProvider;
        $this->filterUrlParser = $filterUrlParser;
        $this->reviewsRepository = $reviewsRepository;
    }


    public function getProductTypePageResponse(Node $node, ProductTypePage $productTypePage, $page)
    {
        $inputSort = \Input::get('sort');

        // Get data provider
        if ($productTypePage->product_list_way == ProductTypePage::WAY_FILTERED) {
            $productListDataProvider = $this->getFilteredProductDataProvider($productTypePage, $node, $inputSort);
        } elseif ($productTypePage->product_list_way == ProductTypePage::WAY_MANUAL) {
            $productListDataProvider = $this->getManualProductData($productTypePage, $node, $inputSort);
        } else {
            $productListDataProvider = null;
        }


        // Get data from provider
        if (!is_null($productListDataProvider)) {
            $productListData = $productListDataProvider->getProductListData($page);
        } else {
            $productListData = null;
        }

        $childrenNodes = $this->nodeRepository->getPublishedChildren($node->id);
        $metaData = \MetaHelper::metaForObject($productTypePage, $node->name);
        $metaData = \MetaHelper::appendPagination($metaData, $productListData['paginationData']);
        $breadcrumbs = $this->getBreadcrumbs($node);

        $category = $this->catalogCategoryRepository->findById($productTypePage->parent_category_id);
        $sidebar = null;
        if (!empty($category->content_for_sidebar)) {
            $sidebar = $category->content_for_sidebar;
        }

        $reviews = $this->reviewsRepository->getPublishedForProductTypePage($productTypePage);
        $products = $this->catalogProductRepository->getAllByIds($reviews->lists('product_id'));
        $hideRegionsInPage = $node->hide_regions_in_page;
        return \View::make('client.product_type_page.page')
            ->with('productTypePage', $productTypePage)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('productListData', $productListData)
            ->with('childrenNodes', $childrenNodes)
            ->with('page_type', 'categorypage')
            ->with('hideRegionsInPage', $hideRegionsInPage)
            ->with('show_small_content', true)
            ->with('sidebar', $sidebar)
            ->with('type_order_button', $productTypePage->type_order_button)
            ->with(compact('reviews', 'products'))
            ->with($metaData);
    }


    /**
     * Get data provider for filtered product list.
     *
     * @param ProductTypePage $productTypePage
     * @param Node $node
     * @param $inputSort
     * @return ProductListDataProvider|null
     */
    private function getFilteredProductDataProvider(ProductTypePage $productTypePage, Node $node, $inputSort)
    {
        try {
            $filterUrl = $productTypePage->filter_query;
            list($filterData, $pageSort) = $this->filterUrlParser->getFilterDataWithoutCategoryFromUrl($filterUrl);
        } catch (IncorrectFilterUrl $e) {
            list($filterData, $pageSort) = [null, null, null];
        }

        if (!is_null($inputSort)) {
            $sort = $inputSort;
        } else {
            $sort = $pageSort;
        }
        if (!is_null($filterData) && (count($filterData) > 0 || $this->catalogProductRepository->checkSortVariant($sort))) {
            $productListDataProvider = new CatalogProductListDataProvider(
                $this->attributesOutputDataProvider,
                new FilteredProductTypePaginationDataProvider(
                    $this->catalogProductRepository,
                    $productTypePage,
                    $filterData,
                    $sort
                ),
                new ProductTypePageAdditionalInfo($this->prodTypePageAssociationRepo, $productTypePage),
                new DefaultSortingDataProvider($this->catalogProductRepository, $sort),
                new CatalogFilterDataProvider($this->catalogProductRepository, $filterData),
                new ProductTypePageUrlDataProvider($node, $sort),
                new PaginationFactory()
            );
        } else {
            $productListDataProvider = new CatalogProductListDataProvider(
                $this->attributesOutputDataProvider,
                new EmptyPaginationDataProvider(),
                new ProductTypePageAdditionalInfo($this->prodTypePageAssociationRepo, $productTypePage),
                new DefaultSortingDataProvider($this->catalogProductRepository, $sort),
                new CatalogFilterDataProvider($this->catalogProductRepository),
                new ProductTypePageUrlDataProvider($node, $sort),
                new PaginationFactory()
            );
        }

        return $productListDataProvider;
    }

    /**
     * Get data provider for manual product list.
     *
     * @param ProductTypePage $productTypePage
     * @param Node $node
     * @param $sort
     * @return ProductListDataProvider|null
     */
    private function getManualProductData(ProductTypePage $productTypePage, Node $node, $sort)
    {
        $category = $this->catalogCategoryRepository->getManualCategoryForProductTypePage($productTypePage);

        if (is_null($category)) {
            $productListDataProvider = $this->defaultProductData(
                $productTypePage,
                $node,
                $productTypePage->manual_product_list_category_id,
                $sort
            );
        } else {
            $productListDataProvider = new ProductListDataProvider(
                $this->attributesOutputDataProvider,
                $category,
                new ManualPaginationDataProvider($this->catalogProductRepository, $productTypePage, $sort),
                new ProductTypePageAdditionalInfo($this->prodTypePageAssociationRepo, $productTypePage),
                new DefaultSortingDataProvider($this->catalogProductRepository, $sort),
                new DefaultFilterDataProvider($this->catalogProductRepository, $category),
                new ProductTypePageUrlDataProvider($node, $sort),
                new PaginationFactory()
            );
        }

        return $productListDataProvider;
    }

    /**
     * Get default data provider for product type. It will provide appropriate filter with no products.
     *
     * @param ProductTypePage $productTypePage
     * @param Node $node
     * @param $categoryId
     * @param $sort
     * @return ProductListDataProvider|null
     */
    private function defaultProductData(ProductTypePage $productTypePage, Node $node, $categoryId, $sort)
    {
        $category = $this->catalogCategoryRepository->getRoot($categoryId);
        if (is_null($category)) {
            $category = array_get($this->catalogCategoryRepository->rootedElements(), 0);
        }

        if (is_null($category)) {
            $productListDataProvider = null;
        } else {
            $productListDataProvider = new ProductListDataProvider(
                $this->attributesOutputDataProvider,
                $category,
                new EmptyPaginationDataProvider(),
                new ProductTypePageAdditionalInfo($this->prodTypePageAssociationRepo, $productTypePage),
                new DefaultSortingDataProvider($this->catalogProductRepository, $sort),
                new DefaultFilterDataProvider($this->catalogProductRepository, $category),
                new ProductTypePageUrlDataProvider($node),
                new PaginationFactory()
            );
        }

        return $productListDataProvider;
    }
}
