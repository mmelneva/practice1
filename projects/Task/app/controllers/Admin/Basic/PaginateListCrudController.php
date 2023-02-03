<?php namespace App\Controllers\Admin\Basic;

use App\Services\FormProcessors\CrudFormProcessorInterface;
use App\Services\Pagination\SimplePaginationFactory;
use App\Services\Repositories\PaginateListRepositoryInterface;
use App\Services\Repositories\ToggleableRepositoryInterface;

/**
 * Class PaginateListCrudController
 * @property PaginateListRepositoryInterface $repository
 * @package App\Controllers\Admin\Basic
 */
abstract class PaginateListCrudController extends ListCrudController
{
    protected $simplePaginationFactory;

    public function __construct(
        PaginateListRepositoryInterface $repository,
        CrudFormProcessorInterface $formProcessor,
        SimplePaginationFactory $simplePaginationFactory
    ) {
        parent::__construct($repository, $formProcessor);

        $this->simplePaginationFactory = $simplePaginationFactory;
    }

    protected function getResourceList()
    {
        return $this->simplePaginationFactory->make($this->repository->byPage(\Input::get('page')));
    }

    public function postStore()
    {
        $redirect = parent::postStore();
        $redirect->setTargetUrl(wrap_with_page($redirect->getTargetUrl(), \Input::get('page')));

        return $redirect;
    }

    public function putUpdate($id = null)
    {
        $redirect = parent::putUpdate($id);
        $redirect->setTargetUrl(wrap_with_page($redirect->getTargetUrl(), \Input::get('page')));

        return $redirect;
    }
}
