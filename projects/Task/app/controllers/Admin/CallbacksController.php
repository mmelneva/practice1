<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Basic\PaginateListCrudController;
use App\Services\FormProcessors\CallbackFormProcessor;
use App\Services\Pagination\SimplePaginationFactory;
use App\Services\Repositories\Callback\CallbackRepositoryInterface;

/**
 * Class CallbacksController
 * @property-read CallbackRepositoryInterface $repository
 * @package  App\Controllers\Admin
 */
class CallbacksController extends PaginateListCrudController
{
    public function __construct(
        CallbackRepositoryInterface $repository,
        CallbackFormProcessor $formProcessor,
        SimplePaginationFactory $simplePaginationFactory
    ) {
        parent::__construct($repository, $formProcessor, $simplePaginationFactory);
    }

    protected function checkNoCreate()
    {
        return true;
    }

    protected function getFormData(\Eloquent $resource)
    {
        return [
            'status_list' => $this->repository->getStatusVariants(),
            'type_list' => $this->repository->getTypeVariants()
        ];
    }

    protected function getTexts()
    {
        return [
            'list_title' => 'Заявки',
            'edit_title' => 'Редактирование заявки "{name}"',
            'delete_confirm' => 'Вы уверены, что хотите удалить данную заявку?',
        ];
    }

    protected function getMessages()
    {
        return [
            'updated' => 'Заявка "{name}" обновлена',
            'deleted' => 'Заявка "{name}" удалена',
        ];
    }

    protected function replaceName($resource, $message)
    {
        return str_replace('"{name}"', '№' . $resource->id, $message);
    }

    public function getViewConfiguration()
    {
        $conf = parent::getViewConfiguration();

        $conf['list'] = [
            [
                'field' => 'id',
                'template' => 'admin.list_columns._text_field',
                'header_template' => 'admin.callbacks.list_column_headers._number_header',
            ],
            [
                'field' => 'type',
                'template' => 'admin.callbacks.list_columns._type_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'field' => 'callback_status',
                'template' => 'admin.callbacks.list_columns._status_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'field' => 'name',
                'template' => 'admin.list_columns._full_name_field',
                'header_template' => 'admin.list_column_headers._full_name_header',
            ],
            [
                'field' => 'phone',
                'template' => 'admin.list_columns._text_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'field' => 'url_referer',
                'template' => 'admin.callbacks.list_columns._url_field',
                'header_template' => 'admin.callbacks.list_column_headers._url_referer_header',
            ],
            [
                'field' => 'created_at',
                'template' => 'admin.list_columns._date_field',
                'header_template' => 'admin.list_column_headers._standard_header',
            ],
            [
                'template' => 'admin.list_columns._controls',
                'header_template' => 'admin.list_column_headers._control_header'
            ],
        ];

        $conf['menu'] = [
            ['field' => 'name', 'template' => 'admin.list_columns._full_name_field'],
            [
                'template' => 'admin.list_columns._controls',
                'header_template' => 'admin.list_column_headers._control_header'
            ]
        ];

        $conf['form'] = [
            ['field' => 'id', 'template' => 'admin.callbacks.form._number_field'],
            ['field' => 'type', 'template' => 'admin.callbacks.form._type_field'],
            ['field' => 'callback_status', 'template' => 'admin.callbacks.form._status_select_field'],
            ['field' => 'name', 'template' => 'admin.resource_fields._full_name_field'],
            ['field' => 'phone', 'template' => 'admin.resource_fields._phone_field'],
            ['field' => 'url_referer', 'template' => 'admin.callbacks.form._url_field'],
            ['field' => 'appropriate_time', 'template' => 'admin.resource_fields._text_field'],
            ['field' => 'address', 'template' => 'admin.callbacks.form._address_field'],
            ['field' => 'comment', 'template' => 'admin.resource_fields._text_area_field'],
            ['template' => 'admin.resource_fields._timestamps'],
        ];

        return $conf;
    }
}
