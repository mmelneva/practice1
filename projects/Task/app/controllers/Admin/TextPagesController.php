<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TextPage;
use App\Services\Providers\StructureTypesServiceProvider;
use App\Services\Repositories\Node\NodeRepositoryInterface;
use App\Services\Repositories\TextPage\TextPageRepositoryInterface;

class TextPagesController extends BaseController
{
    private $nodeRepository;
    private $textPageRepository;

    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        TextPageRepositoryInterface $textPageRepository
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->textPageRepository = $textPageRepository;
    }

    public function getEdit($nodeId = null)
    {
        $textPage = $this->getTextPage($nodeId);

        return \View::make('admin.text_pages.edit')
            ->with('textPage', $textPage)
            ->with('node', $textPage->node)
            ->with('nodeTree', $this->nodeRepository->getCollapsedTree($nodeId));
    }

    public function putUpdate($nodeId = null)
    {
        $textPage = $this->getTextPage($nodeId);
        $textPage->fill(\Input::all());
        $textPage->save();

        if (\Input::get('redirect_to') == 'index') {
            $redirect = \Redirect::action('App\Controllers\Admin\StructureController@getIndex');
        } else {
            $redirect = \Redirect::action(get_called_class() . '@getEdit', [$nodeId]);
        }
        return $redirect->with('alert_success', 'Страница обновлена');
    }


    private function getTextPage($nodeId)
    {
        $node = $this->nodeRepository->findById($nodeId);
        if (is_null($node)) {
            \App::abort(404, 'Node not found');
        }

        $textPage = \TypeContainer::getContentModelFor($node);
        if ($textPage instanceof TextPage === false) {
            \App::abort(404, 'Text page not found');
        }

        return $textPage;
    }
}
