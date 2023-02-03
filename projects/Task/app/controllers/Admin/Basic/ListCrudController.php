<?php namespace App\Controllers\Admin\Basic;

use App\Controllers\BaseController;
use App\Services\FormProcessors\CrudFormProcessorInterface;
use App\Services\Repositories\ListRepositoryInterface;

/**
 * Class ListCrudController
 * @package App\Controllers\Admin\Basic
 */
abstract class ListCrudController extends BaseController
{
    /**
     * @var ListRepositoryInterface
     */
    protected $repository;

    /**
     * @var CrudFormProcessorInterface
     */
    protected $formProcessor;

    /**
     * @var array
     */
    protected $viewConfiguration;

    /**
     * @var array
     */
    protected $resourceTexts;

    /**
     * @var array
     */
    protected $resourceMessages;

    protected $noCreate;

    public function __construct(ListRepositoryInterface $repository, CrudFormProcessorInterface $formProcessor)
    {
        $this->repository = $repository;
        $this->formProcessor = $formProcessor;

        $this->viewConfiguration = $this->getViewConfiguration();
        $this->resourceTexts = $this->getTexts();
        $this->resourceMessages = $this->getMessages();
        $this->noCreate = $this->checkNoCreate();
    }

    protected function checkNoCreate()
    {
        return false;
    }

    protected function getResourceList()
    {
        return $this->repository->all();
    }

    /**
     * @param \Eloquent|mixed $resource
     * @return array
     */
    protected function getFormData(\Eloquent $resource)
    {
        return [];
    }

    /**
     * Get string uid for repository.
     *
     * Example: for repository with '..\EloquentOrderRepository' class will return 'order' as uid.
     *
     * @param $repository
     * @return string
     */
    protected function getHumanizedRepositoryUid($repository)
    {
        $shortName = snake_case((new \ReflectionClass($repository))->getShortName());
        return \Str::slug(str_singular(trim(str_replace(['eloquent', 'repository'], '', $shortName), '_')));
    }

    /**
     * Get id for element list.
     *
     * @return string
     */
    protected function getElementListWrapperId()
    {
        return $this->getHumanizedRepositoryUid($this->repository) . '-list';
    }

    /**
     * Get id for restful form.
     *
     * @return string
     */
    protected function getRestfulFormId()
    {
        return $this->getHumanizedRepositoryUid($this->repository);
    }

    public function getViewConfiguration()
    {
        return [
            'list' => [
                ['field' => 'name', 'template' => 'admin.list_columns._text_field_link'],
                [
                    'template' => 'admin.list_columns._controls',
                    'header_template' => 'admin.list_column_headers._control_header'
                ],
            ],
            'menu' => [
                ['field' => 'name', 'template' => 'admin.list_columns._text_field_link'],
                [
                    'template' => 'admin.list_columns._controls',
                    'header_template' => 'admin.list_column_headers._control_header'
                ],
            ],
            'form' => [
                ['field' => 'name', 'template' => 'admin.resource_fields._text_field'],
                ['template' => 'admin.resource_fields._timestamps'],
            ]
        ];
    }

    abstract protected function getTexts();

    abstract protected function getMessages();

    public function getIndex()
    {
        $template = array_get($this->viewConfiguration, 'templates.index', 'admin.resource_list.index');

        return \View::make($template)
            ->with('resource_controller', get_called_class())
            ->with('resource_list', $this->getResourceList())
            ->with('list_columns', $this->viewConfiguration['list'])
            ->with('resource_texts', $this->resourceTexts)
            ->with('no_create', $this->noCreate)
            ->with('element_list_wrapper_id', $this->getElementListWrapperId());

    }

    public function getCreate()
    {
        if ($this->noCreate) {
            \App::abort(404, 'Resource not found');
        }

        $resource = $this->repository->newInstance();

        $template = array_get($this->viewConfiguration, 'templates.create', 'admin.resource_list.create');

        return \View::make($template)
            ->with('resource_controller', get_called_class())
            ->with('resource', $resource)
            ->with('resource_list', $this->getResourceList())
            ->with('menu_columns', $this->viewConfiguration['menu'])
            ->with('form_fields', $this->viewConfiguration['form'])
            ->with('resource_texts', $this->resourceTexts)
            ->with('restful_form_id', $this->getRestfulFormId())
            ->with($this->getFormData($resource));
    }

    public function getEdit($id = null)
    {
        $resource = $this->repository->findById($id);
        if (is_null($resource)) {
            \App::abort(404, 'Resource not found');
        }
        $template = array_get($this->viewConfiguration, 'templates.edit', 'admin.resource_list.edit');

        return \View::make($template)
            ->with('resource_controller', get_called_class())
            ->with('resource', $resource)
            ->with('resource_list', $this->getResourceList())
            ->with('menu_columns', $this->viewConfiguration['menu'])
            ->with('form_fields', $this->viewConfiguration['form'])
            ->with('resource_texts', $this->resourceTexts)
            ->with('no_create', $this->noCreate)
            ->with('restful_form_id', $this->getRestfulFormId())
            ->with($this->getFormData($resource));
    }

    public function postStore()
    {
        if ($this->noCreate) {
            \App::abort(404, 'Resource not found');
        }

        $resource = $this->formProcessor->create(\Input::except('redirect_to'));
        if (is_null($resource)) {
            return \Redirect::action(get_called_class() . '@getCreate')
                ->withErrors($this->formProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$resource['id']]);
            }

            return $redirect->with(
                'alert_success',
                $this->replaceName($resource, array_get($this->resourceMessages, 'created', 'Ресурс создан'))
            );
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

            return $redirect->with(
                'alert_success',
                $this->replaceName($resource, array_get($this->resourceMessages, 'updated', 'Ресурс обновлён'))
            );
        }
    }

    public function deleteDestroy($id = null)
    {
        $resource = $this->repository->findById($id);
        if (is_null($resource)) {
            \App::abort(404, 'Resource not found');
        }
        $this->repository->delete($resource->id);

        return \Redirect::action(get_called_class() . '@getIndex')
            ->with(
                'alert_success',
                $this->replaceName($resource, array_get($this->resourceMessages, 'deleted', 'Ресурс удалён'))
            );
    }

    protected function replaceName($resource, $message)
    {
        return str_replace('{name}', $resource->name, $message);
    }
}
