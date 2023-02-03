<?php namespace App\Controllers\Admin\Basic;

use \App\Services\Repositories\SortableListRepositoryInterface;
use App\Services\FormProcessors\CrudFormProcessorInterface;

abstract class SortableListCrudController extends ListCrudController
{
    /**
     * @var SortableListRepositoryInterface
     */
    protected $repository;

    public function __construct(SortableListRepositoryInterface $repository, CrudFormProcessorInterface $formProcessor)
    {
        parent::__construct($repository, $formProcessor);
    }

    public function getIndex()
    {
        if (\Request::ajax()) {
            $content = \View::make('admin.resource_list_sortable._list')
                ->with('resource_controller', get_called_class())
                ->with('resource_list', $this->getResourceList())
                ->with('list_columns', $this->viewConfiguration['list'])
                ->with('resource_texts', $this->resourceTexts)
                ->render();

            return \Response::json(['element_list' => $content]);
        } else {
            return \View::make('admin.resource_list_sortable.index')
                ->with('resource_controller', get_called_class())
                ->with('resource_list', $this->getResourceList())
                ->with('list_columns', $this->viewConfiguration['list'])
                ->with('resource_texts', $this->resourceTexts)
                ->with('element_list_wrapper_id', $this->getElementListWrapperId());
        }
    }

    public function putUpdatePositions()
    {
        $this->repository->updatePositions(\Input::get('positions', []));
        if (\Request::ajax()) {
            return \Response::json(['status' => 'alert_success']);
        } else {
            return \Redirect::action(get_called_class() . '@getIndex');
        }
    }
}
