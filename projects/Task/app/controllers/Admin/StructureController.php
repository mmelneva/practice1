<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Features\ToggleFlags;
use App\Controllers\BaseController;
use App\Services\FormProcessors\NodeFormProcessor;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class StructureController extends BaseController
{
    use ToggleFlags;

    /**
     * @var NodeRepositoryInterface
     */
    private $repository;

    /**
     * @var NodeFormProcessor
     */
    private $formProcessor;


    public function __construct(
        NodeRepositoryInterface $repository,
        NodeFormProcessor $formProcessor
    ) {
        $this->repository = $repository;
        $this->formProcessor = $formProcessor;
    }


    public function getIndex()
    {
        $nodeTree = $this->repository->getTree();
        if (\Request::ajax()) {
            $content = \View::make('admin.structure._node_list')
                ->with('nodeTree', $nodeTree)
                ->with('lvl', 0)
                ->render();

            return \Response::json(['element_list' => $content]);

        } else {
            return \View::make('admin.structure.index')
                ->with('nodeTree', $nodeTree);
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

    public function putToggleAttribute($id = null, $attribute = null)
    {
        return $this->checkAndPrepareToggleFlag(
            $this->repository,
            $id,
            $attribute,
            ['publish', 'menu_top', 'scrolled_menu_top', 'menu_bottom',]
        );
    }


    public function getCreate()
    {
        $node = $this->repository->newInstance();

        return \View::make('admin.structure.create')
            ->with('node', $node)
            ->with('parentVariants', $this->repository->getParentVariants(null, '[Корень]'))
            ->with('nodeTree', $this->repository->getCollapsedTree());
    }

    public function getEdit($id = null)
    {
        $node = $this->repository->findById($id);
        if (is_null($node)) {
            \App::abort(404, 'Node not found');
        }

        return \View::make('admin.structure.edit')
            ->with('node', $node)
            ->with('parentVariants', $this->repository->getParentVariants($id, '[Корень]'))
            ->with('nodeTree', $this->repository->getCollapsedTree($node->id));
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
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$createdResource['id']]);
            }

            return $redirect->with('alert_success', trans('Страница создана'));
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

            return $redirect->with('alert_success', trans('Страница обновлена'));
        }
    }

    public function deleteDestroy($id = null)
    {
        if (is_null($this->repository->findById($id))) {
            \App::abort(404, 'Resource not found');
        }
        $this->repository->delete($id);

        return \Redirect::action(get_called_class() . '@getIndex')->with('alert_success', 'Страница удалена');
    }
}
