<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Features\ToggleFlags;
use App\Controllers\BaseController;
use App\Services\DataProviders\CatalogProduct\AdditionalAttributesDataProvider;
use App\Services\DataProviders\CatalogProduct\AssociatedCategoriesDataProvider;
use App\Services\FormProcessors\CatalogProductFormProcessor;
use App\Services\Helpers\PaginatorHelper;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Repositories\ProductGalleryImage\ProductGalleryImageRepositoryInterface;
use App\Services\DataProviders\CatalogProduct\ProductGalleryImagesDataProvider;

class CatalogProductsController extends BaseController
{
    use ToggleFlags;

    /**
     * @var CatalogCategoryRepositoryInterface
     */
    private $catalogCategoryRepository;

    /**
     * @var CatalogProductRepositoryInterface
     */
    private $catalogProductRepository;

    /**
     * @var CatalogProductFormProcessor
     */
    protected $formProcessor;

    /**
     * @var AdditionalAttributesDataProvider
     */
    private $additionalAttributesDataProvider;

    /**
     * @var PaginatorHelper
     */
    private $paginatorHelper;

    /**
     * @var ProductGalleryImageRepositoryInterface
     */
    private $galleryImageRepository;

    /**
     * @var ProductGalleryImagesDataProvider
     */
    private $galleryImagesDataProvider;

    /**
     * @var AssociatedCategoriesDataProvider
     */
    private $associatedCategoriesDataProvider;

    /**
     * @param CatalogCategoryRepositoryInterface $catalogCategoryRepository
     * @param CatalogProductRepositoryInterface $catalogProductRepository
     * @param CatalogProductFormProcessor $formProcessor
     * @param AdditionalAttributesDataProvider $additionalAttributesDataProvider
     * @param PaginatorHelper $paginatorHelper
     * @param ProductGalleryImageRepositoryInterface $galleryImageRepository
     * @param ProductGalleryImagesDataProvider $galleryImagesDataProvider
     * @param AssociatedCategoriesDataProvider $associatedCategoriesDataProvider
     */
    public function __construct(
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogProductRepositoryInterface $catalogProductRepository,
        CatalogProductFormProcessor $formProcessor,
        AdditionalAttributesDataProvider $additionalAttributesDataProvider,
        PaginatorHelper $paginatorHelper,
        ProductGalleryImageRepositoryInterface $galleryImageRepository,
        ProductGalleryImagesDataProvider $galleryImagesDataProvider,
        AssociatedCategoriesDataProvider $associatedCategoriesDataProvider
    ) {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->catalogProductRepository = $catalogProductRepository;
        $this->formProcessor = $formProcessor;
        $this->additionalAttributesDataProvider = $additionalAttributesDataProvider;
        $this->paginatorHelper = $paginatorHelper;
        $this->galleryImageRepository = $galleryImageRepository;
        $this->galleryImagesDataProvider = $galleryImagesDataProvider;
        $this->associatedCategoriesDataProvider = $associatedCategoriesDataProvider;
    }

    public function getIndex($categoryId = null)
    {
        $category = $this->catalogCategoryRepository->findById($categoryId);
        if (is_null($category)) {
            \App::abort(404, 'Category not found');
        }

        $categoryTree = $this->catalogCategoryRepository->getCollapsedTree($category->id);

        $onPageVariants = [25, 50, 100, 250];
        $this->paginatorHelper->preparePageFor('catalog_products_' . $categoryId);
        $onPage = $this->paginatorHelper->getOnPage('catalog_products_' . $categoryId, $onPageVariants);

        $productsData = $this->catalogProductRepository->allInCategoryByPage($categoryId, $onPage);
        $productList = $productsData['productList'];
        $previousItemsCount = $productsData['previousItemsCount'];

        if (\Request::ajax()) {
            $content = \View::make('admin.catalog_products._product_list')
                ->with(compact('category', 'productList'))->render();

            return \Response::json(['element_list' => $content]);
        } else {
            return \View::make('admin.catalog_products.index')
                ->with(compact('category', 'categoryTree', 'productList'))
                ->with('on_page_variants', $onPageVariants)
                ->with('position_offset', $previousItemsCount * 10);
        }
    }

    public function putUpdatePositions()
    {
        $this->catalogProductRepository->updatePositions(\Input::get('positions', []));
        if (\Request::ajax()) {
            return \Response::json(['status' => 'alert_success']);
        } else {
            return \Redirect::action(get_called_class() . '@getIndex');
        }
    }

    public function putToggleAttribute($id = null, $attribute = null)
    {
        return $this->checkAndPrepareToggleFlag(
            $this->catalogProductRepository,
            $id,
            $attribute,
            ['publish']
        );
    }


    public function getCreate($categoryId = null)
    {
        $category = $this->catalogCategoryRepository->findById($categoryId);
        if (is_null($category)) {
            \App::abort(404, 'Category not found');
        }

        $categoryTree = $this->catalogCategoryRepository->getCollapsedTree($category->id);
        $product = $this->catalogProductRepository->newInstance(['category_id' => $categoryId]);
        $categoryVariants = $this->catalogCategoryRepository->getParentVariants(null);

        $view = \View::make('admin.catalog_products.create')
            ->with(compact('category', 'categoryTree', 'product', 'categoryVariants'));

        return $this->fillView($view, $product);
    }

    public function getEdit($productId = null)
    {
        $product = $this->catalogProductRepository->findById($productId);
        if (is_null($product)) {
            \App::abort(404, 'Product not found');
        }

        $category = $product->category;
        $categoryTree = $this->catalogCategoryRepository->getCollapsedTree($category->id);
        $categoryVariants = $this->catalogCategoryRepository->getParentVariants(null);

        $view = \View::make('admin.catalog_products.edit')
            ->with(compact('category', 'categoryTree', 'product', 'categoryVariants'));

        return $this->fillView($view, $product);
    }

    protected function fillView($view, $product)
    {
        return $view
            ->with($this->additionalAttributesDataProvider->getAdditionalAttributesFormData($product, \Input::old()))
            ->with($this->galleryImagesDataProvider->getGalleryImagesFormData($product, \Input::old()))
            ->with($this->associatedCategoriesDataProvider->getAssociatedCategoriesFormData($product))
            ->with('builtInVariants', $this->catalogProductRepository->getBuiltInVariants());
    }

    public function postStore($categoryId = null)
    {
        $category = $this->catalogCategoryRepository->findById($categoryId);
        if (is_null($category)) {
            \App::abort(404, 'Category not found');
        }

        $product = $this->formProcessor->create(\Input::except('redirect_to'));
        if (is_null($product)) {
            return \Redirect::action(get_called_class() . '@getCreate', [$categoryId])
                ->withErrors($this->formProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex', [$product->category_id]);
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$product->id]);
            }

            return $redirect->with('alert_success', trans('Товар создан'));
        }
    }

    public function putUpdate($productId = null)
    {
        $product = $this->formProcessor->update($productId, \Input::except('redirect_to'));
        if (is_null($product)) {
            return \Redirect::action(get_called_class() . '@getEdit', [$productId])
                ->withErrors($this->formProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex', [$product->category_id]);
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$productId]);
            }

            return $redirect->with('alert_success', trans('Товар обновлён'));
        }
    }


    public function deleteDestroy($productId = null)
    {
        $product = $this->catalogProductRepository->findById($productId);
        $categoryId = $product->category_id;
        if (is_null($product)) {
            \App::abort(404, 'Product not found');
        }
        $this->catalogProductRepository->delete($productId);

        return \Redirect::action(get_called_class() . '@getIndex', [$categoryId])
            ->with('alert_success', 'Товар удалён');
    }

    public function getGalleryImageElement()
    {
        $content = \View::make('admin.catalog_products._gallery_images._image_element')
            ->with('key', \Input::get('key', 0))
            ->with('galleryImage', $this->galleryImageRepository->newInstance())
            ->render();

        return \Response::json(['element' => $content]);
    }
}
