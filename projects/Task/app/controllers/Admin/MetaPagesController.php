<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MetaPage;
use App\Services\Repositories\Node\NodeRepositoryInterface;

class MetaPagesController extends BaseController
{
    private $nodeRepository;

    public function __construct(
        NodeRepositoryInterface $nodeRepository
    ) {
        $this->nodeRepository = $nodeRepository;
    }

    public function getEdit($nodeId = null)
    {
        $metaPage = $this->getMetaPage($nodeId);

        return \View::make('admin.meta_pages.edit')
            ->with('metaPage', $metaPage)
            ->with('node', $metaPage->node)
            ->with('nodeTree', $this->nodeRepository->getCollapsedTree($nodeId));
    }

    public function putUpdate($nodeId = null)
    {
        $metaPage = $this->getMetaPage($nodeId);
        $metaPage->fill(\Input::all());
        $metaPage->save();

        if (\Input::get('redirect_to') == 'index') {
            $redirect = \Redirect::action('App\Controllers\Admin\StructureController@getIndex');
        } else {
            $redirect = \Redirect::action(get_called_class() . '@getEdit', [$nodeId]);
        }
        return $redirect->with('alert_success', 'Страница обновлена');
    }

    private function getMetaPage($nodeId)
    {
        $node = $this->nodeRepository->findById($nodeId);
        if (is_null($node)) {
            \App::abort(404, 'Node not found');
        }

        $metaPage = \TypeContainer::getContentModelFor($node);
        if ($metaPage instanceof MetaPage === false) {
            \App::abort(404, 'Meta page not found');
        }

        return $metaPage;
    }
}
