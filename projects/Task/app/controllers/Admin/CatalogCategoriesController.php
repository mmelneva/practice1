<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Features\ToggleFlags;
use App\Controllers\BaseController;
use App\Services\FormProcessors\CatalogCategoryFormProcessor;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface;

class CatalogCategoriesController extends BaseController
{
    use ToggleFlags;

    private $catalogCategoryRepository;
    private $formProcessor;
    private $productTypePageRepository;

    public function __construct(
        CatalogCategoryRepositoryInterface $catalogCategoryRepository,
        CatalogCategoryFormProcessor $formProcessor,
        ProductTypePageRepositoryInterface $productTypePageRepository
    ) {
        $this->catalogCategoryRepository = $catalogCategoryRepository;
        $this->formProcessor = $formProcessor;
        $this->productTypePageRepository = $productTypePageRepository;
    }


    public function getIndex()
    {
        $categoryTree = $this->catalogCategoryRepository->getTree();

        if (\Request::ajax()) {
            $content = \View::make('admin.catalog_categories._category_list')
                ->with('categoryTree', $categoryTree)
                ->with('lvl', 0)
                ->render();

            return \Response::json(['element_list' => $content]);
        } else {
            return \View::make('admin.catalog_categories.index')
                ->with('categoryTree', $categoryTree);
        }
    }

    public function putUpdatePositions()
    {
        $this->catalogCategoryRepository->updatePositions(\Input::get('positions', []));
        if (\Request::ajax()) {
            return \Response::json(['status' => 'alert_success']);
        } else {
            return \Redirect::action(get_called_class() . '@getIndex');
        }
    }

    public function putToggleAttribute($id = null, $attribute = null)
    {
        return $this->checkAndPrepareToggleFlag(
            $this->catalogCategoryRepository,
            $id,
            $attribute,
            ['publish', 'top_menu', 'use_reviews_associations']
        );
    }


    public function getCreate()
    {
        $category = $this->catalogCategoryRepository->newInstance();
        $view = \View::make('admin.catalog_categories.create')
            ->with('parentVariants', $this->catalogCategoryRepository->getParentVariants(null, '[Корень]'))
            ->with('categoryTree', $this->catalogCategoryRepository->getCollapsedTree());

        return $this->fillView($view, $category, null);
    }

    public function getEdit($id = null)
    {
        $category = $this->catalogCategoryRepository->findById($id);
        if (is_null($category)) {
            \App::abort(404, 'Category not found');
        }

        $view = \View::make('admin.catalog_categories.edit')
            ->with('parentVariants', $this->catalogCategoryRepository->getParentVariants($id, '[Корень]'))
            ->with('categoryTree', $this->catalogCategoryRepository->getCollapsedTree($category->id));

        return $this->fillView($view, $category, $category->id);
    }

    private function fillView($view, $category, $categoryId)
    {
        $this->productTypePageRepository->associateProductTypePageToNode();
        return $view
            ->with('category', $category);

    }

    public function postStore()
    {
        $createdResource = $this->formProcessor->create(\Input::except('redirect_to'));
        if (is_null($createdResource)) {
            return \Redirect::action(get_called_class() . '@getCreate')
                ->withErrors($this->formProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$createdResource->id]);
            }

            return $redirect->with('alert_success', trans('Категория создана'));
        }
    }

    public function putUpdate($id = null)
    {
        $resource = $this->formProcessor->update($id, \Input::except('redirect_to'));
        if (is_null($resource)) {
            return \Redirect::action(get_called_class() . '@getEdit', [$id])
                ->withErrors($this->formProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$id]);
            }

            return $redirect->with('alert_success', trans('Категория обновлена'));
        }
    }


    public function deleteDestroy($id = null)
    {
        if (is_null($this->catalogCategoryRepository->findById($id))) {
            \App::abort(404, 'Category not found');
        }
        $this->catalogCategoryRepository->delete($id);

        return \Redirect::action(get_called_class() . '@getIndex')->with('alert_success', 'Категория удалена');
    }
}
