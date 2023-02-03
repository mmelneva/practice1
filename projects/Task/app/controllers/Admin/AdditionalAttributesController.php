<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Basic\SortableListCrudController;
use App\Controllers\Admin\Features\ToggleFlags;
use App\Services\DataProviders\Attribute\AllowedValuesDataProvider;
use App\Services\FormProcessors\AttributeFormProcessor;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Repositories\AttributeAllowedValue\AttributeAllowedValueRepositoryInterface;

/**
 * Class AdditionalAttributesController
 * @package  App\Controllers\Admin
 */
class AdditionalAttributesController extends SortableListCrudController
{
    use ToggleFlags;

    private $attributeAllowedValueRepository;
    private $allowedValuesDataProvider;

    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeFormProcessor $formProcessor,
        AttributeAllowedValueRepositoryInterface $attributeAllowedValueRepository,
        AllowedValuesDataProvider $allowedValuesDataProvider
    ) {
        parent::__construct($repository, $formProcessor);

        $this->attributeAllowedValueRepository = $attributeAllowedValueRepository;
        $this->allowedValuesDataProvider = $allowedValuesDataProvider;
    }

    protected function getTexts()
    {
        return [
            'list_title' => 'Параметры товаров',
            'add_new' => 'Добавить параметр',
            'edit_title' => 'Редактирование параметра "{name}"',
            'create_title' => 'Создание нового параметра',
            'delete_confirm' => 'Вы уверены, что хотите удалить данный параметр?',
        ];
    }

    protected function getMessages()
    {
        return [
            'created' => 'Новый параметр "{name}" успешно создан',
            'updated' => 'Параметр "{name}" обновлен',
            'deleted' => 'Параметр "{name}" удален',
        ];
    }

    public function getViewConfiguration()
    {
        $conf = parent::getViewConfiguration();

        $conf['list'] = [
            ['field' => 'name', 'template' => 'admin.list_columns._text_field_link'],
            [
                'field' => 'on_product_page',
                'template' => 'admin.list_columns._toggle_attribute',
                'header_template' => 'admin.list_column_headers._toggle_attribute_header',
            ],
            [
                'field' => 'use_in_similar_products',
                'template' => 'admin.list_columns._toggle_attribute',
                'header_template' => 'admin.list_column_headers._toggle_attribute_header',
            ],
            [
                'template' => 'admin.list_columns._controls',
                'header_template' => 'admin.list_column_headers._control_header'
            ],
        ];

        $conf['form'] = [
            ['field' => 'name', 'template' => 'admin.resource_fields._text_field'],
            ['field' => 'type', 'template' => 'admin.additional_attributes.form._type_select_field'],
            ['field' => 'on_product_page', 'template' => 'admin.resource_fields._checkbox_field'],
            ['field' => 'use_in_similar_products', 'template' => 'admin.resource_fields._checkbox_field'],
            ['field' => 'similar_products_name', 'template' => 'admin.resource_fields._text_field'],
            ['template' => 'admin.additional_attributes.form._allowed_values_field'],
            ['field' => 'position', 'template' => 'admin.resource_fields._text_field'],
            ['template' => 'admin.resource_fields._timestamps'],
        ];

        return $conf;
    }

    public function getIndex()
    {
        $response = parent::getIndex();

        if (!\Request::ajax()) {
            $response->with('element_list_wrapper_id', 'additional-attributes-list');
        }

        return $response;
    }

    public function getCreate()
    {
        $view = parent::getCreate()->with(['form_id' => 'additional-attribute']);

        return $this->fillView($view, $this->repository->newInstance());
    }

    public function getEdit($id = null)
    {
        $view = parent::getEdit($id)->with(['form_id' => 'additional-attribute']);

        return $this->fillView($view, $this->repository->findById($id));
    }

    private function fillView($view, $resource)
    {
        return $view->with('types', $this->repository->getTypeVariants())
            ->with($this->allowedValuesDataProvider->getAllowedValuesFormData($resource, \Input::old()));
    }

    public function putToggleAttribute($id = null, $attribute = null)
    {
        return $this->checkAndPrepareToggleFlag(
            $this->repository,
            $id,
            $attribute,
            ['on_product_page', 'use_in_similar_products']
        );
    }

    public function getAllowedValueElement()
    {
        $content = \View::make('admin.additional_attributes.form._allowed_value_element')
            ->with('key', \Input::get('key', 0))
            ->with('allowed_value', $this->attributeAllowedValueRepository->newInstance())
            ->render();

        return \Response::json(['element' => $content]);
    }
}
