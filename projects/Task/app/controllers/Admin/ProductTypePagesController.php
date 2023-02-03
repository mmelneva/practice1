<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Features\ToggleFlags;
use App\Controllers\BaseController;
use App\Models\ProductTypePage;
use App\Services\Catalog\FilterUrlParser\Exception\IncorrectFilterUrl;
use App\Services\Catalog\FilterUrlParser\FilterUrlParser;
use App\Services\FormProcessors\ProductTypePageFormProcessor;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;
use App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface;

class ProductTypePagesController extends BaseController
{
    use ToggleFlags;

    private $nodeRepository;
    private $productTypePageRepository;
    private $catalogCategoryRepository;
    private $catalogProductRepository;
    private $productTypePageFormProcessor;
    private $prodTypePageAssociationRepo;
    private $filterUrlParser;

    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        ProductTypePageRepositoryInterface $productTypePageRepository,
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository,
        ProductTypePageFormProcessor $productTypePageFormProcessor,
        ProdTypePageAssociationRepoInterface $prodTypePageAssociationRepo,
        FilterUrlParser $filterUrlParser
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->productTypePageRepository = $productTypePageRepository;
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->productTypePageFormProcessor = $productTypePageFormProcessor;
        $this->prodTypePageAssociationRepo = $prodTypePageAssociationRepo;
        $this->filterUrlParser = $filterUrlParser;
    }


    public function getEdit($nodeId = null)
    {
        $productTypePage = $this->getProductTypePage($nodeId);
        $catalogTree = $this->catalogCategoryRepository->getCatalogTree();

        $rootCategoryVariants = [];
        foreach ($catalogTree as $category) {
            $rootCategoryVariants[$category->id] = $category->name;
        }
        $parentCategorySelectedVariant = $productTypePage->parent_category_id;
        if (!is_null($productTypePage->manual_product_list_category_id)) {
            $manualCategorySelectedVariant = $productTypePage->manual_product_list_category_id;
        } else {
            $manualCategorySelectedVariant = array_get(array_keys($rootCategoryVariants), 0);
        }

        $associations = $this->prodTypePageAssociationRepo->getAssociationsForPage($productTypePage->id);

        $filterQuery = \Input::old('filter_query', $productTypePage->filter_query);
        $filteredProducts = $this->getProductsByFilterString($productTypePage, $filterQuery);

        return \View::make('admin.product_type_pages.edit')
            ->with('catalogTree', $catalogTree)
            ->with('rootCategoryVariants', $rootCategoryVariants)
            ->with('manualCategorySelectedVariant', $manualCategorySelectedVariant)
            ->with('parentCategorySelectedVariant', $parentCategorySelectedVariant)
            ->with('filteredProducts', $filteredProducts)
            ->with('associations', $associations)
            ->with('productTypePage', $productTypePage)
            ->with('node', $productTypePage->node)
            ->with('nodeTree', $this->nodeRepository->getCollapsedTree($nodeId));
    }

    public function getProductAssociationBlock($type = null, $productId = null, $productTypePageId = null)
    {
        if (is_null($type) || is_null($productId)) {
            \App::abort(404, 'Page not found');
        }
        $association = $this->prodTypePageAssociationRepo->findOrNew($productTypePageId, $productId);

        return \View::make('admin.product_type_pages._association_block')
            ->with('type', $type)
            ->with('association', $association)
            ->with('productId', $productId);
    }

    public function getFilteredProducts($productTypePageId = null)
    {
        $productTypePage = $this->productTypePageRepository->findById($productTypePageId);
        if (is_null($productTypePage)) {
            \App::abort(404, 'Page not found');
        }
        $filteredProducts = $this->getProductsByFilterString($productTypePage, \Input::get('filter_string'));
        $associations = $this->prodTypePageAssociationRepo->getAssociationsForPage($productTypePageId);

        return \View::make('admin.product_type_pages._filtered_products')
            ->with('productTypePageId', $productTypePageId)
            ->with('filteredProducts', $filteredProducts)
            ->with('associations', $associations);
    }


    public function putUpdate($nodeId = null)
    {
        $productTypePage = $this->getProductTypePage($nodeId);
        $resource = $this->productTypePageFormProcessor->save($productTypePage, \Input::all());

        if (is_null($resource)) {
            return \Redirect::action(get_called_class() . '@getEdit', [$nodeId])
                ->withErrors($this->productTypePageFormProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action('App\Controllers\Admin\StructureController@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$nodeId]);
            }

            return $redirect->with('alert_success', 'Страница обновлена');
        }
    }


    /**
     * @param $nodeId
     * @return ProductTypePage
     */
    private function getProductTypePage($nodeId)
    {
        $node = $this->nodeRepository->findById($nodeId);
        if (is_null($node)) {
            \App::abort(404, 'Node not found');
        }

        $textPage = \TypeContainer::getContentModelFor($node);
        if ($textPage instanceof ProductTypePage === false) {
            \App::abort(404, 'Text page not found');
        }

        return $textPage;
    }


    /**
     * Get products list by filter string.
     *
     * @param ProductTypePage $productTypePage
     * @param $filterString
     * @return array|mixed
     */
    private function getProductsByFilterString(ProductTypePage $productTypePage, $filterString)
    {
        try {
            list($filterData, $sort) = $this->filterUrlParser->getFilterDataWithoutCategoryFromUrl($filterString);
            if (count($filterData) > 0 || $this->catalogProductRepository->checkSortVariant($sort)) {
                $filteredProducts = $this->catalogProductRepository->allFilteredForProductType(
                    null,
                    $productTypePage,
                    $sort,
                    $filterData
                );
            } else {
                $filteredProducts = [];
            }
        } catch (IncorrectFilterUrl $e) {
            $filteredProducts = [];
        }

        return $filteredProducts;
    }

    public function putToggleAttribute($id = null, $attribute = null)
    {
        return $this->checkAndPrepareToggleFlag(
                $this->productTypePageRepository,
                $id,
                $attribute,
                ['use_reviews_associations']
        );
    }

}
