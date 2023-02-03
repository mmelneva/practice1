<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Basic\PaginateListCrudController;
use App\Services\DataProviders\Order\OrderDataProvider;
use App\Services\FormProcessors\OrderFormProcessor;
use App\Services\Pagination\SimplePaginationFactory;
use App\Services\Repositories\Order\OrderRepositoryInterface;

/**
 * Class OrdersController
 * @property-read OrderRepositoryInterface $repository
 * @package  App\Controllers\Admin
 */
class OrdersController extends PaginateListCrudController
{
    public function __construct(
        OrderRepositoryInterface $repository,
        OrderFormProcessor $formProcessor,
        SimplePaginationFactory $simplePaginationFactory,
        OrderDataProvider $orderDataProvider
    ) {
        parent::__construct($repository, $formProcessor, $simplePaginationFactory);

        $this->orderDataProvider = $orderDataProvider;
    }

    protected function checkNoCreate()
    {
        return true;
    }

    protected function getFormData(\Eloquent $resource)
    {
        return $this->orderDataProvider->getOrderFormData();
    }

    protected function getTexts()
    {
        return [
            'list_title' => 'Заказы',
            'add_new' => 'Добавить заказ',
            'edit_title' => 'Редактирование заказа "{name}"',
            'create_title' => 'Создание нового заказа',
            'delete_confirm' => 'Вы уверены, что хотите удалить данный заказ?',
        ];
    }

    protected function getMessages()
    {
        return [
            'created' => 'Новый заказ "{name}" успешно создан',
            'updated' => 'Заказ "{name}" обновлен',
            'deleted' => 'Заказ "{name}" удален',
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
                'header_template' => 'admin.orders.list_column_headers._number_header',
            ],
            [
                'field' => 'order_status',
                'template' => 'admin.orders.list_columns._status_field',
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
                'field' => 'created_at',
                'template' => 'admin.orders.list_columns._date_time_field',
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
            ['field' => 'id', 'template' => 'admin.orders.form._order_number_field'],
            ['field' => 'name', 'template' => 'admin.resource_fields._full_name_field'],
            ['field' => 'phone', 'template' => 'admin.resource_fields._phone_field'],
            ['field' => 'email', 'template' => 'admin.resource_fields._text_field'],
            ['template' => 'admin.orders.form._product'],
            ['field' => 'comment', 'template' => 'admin.resource_fields._text_area_field'],
            ['field' => 'order_status', 'template' => 'admin.orders.form._order_status_select_field'],

            ['template' => 'admin.resource_fields._timestamps'],
        ];

        return $conf;
    }
}
