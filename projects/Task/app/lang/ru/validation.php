<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Последующие языковые строки содержат сообщения по-умолчанию, используемые
    | классом, проверяющим значения (валидатором).Некоторые из правил имеют
    | несколько версий, например, size. Вы можете поменять их на любые
    | другие, которые лучше подходят для вашего приложения.
    |
    */

    "accepted" => "Вы должны принять :attribute.",
    "active_url" => "Поле :attribute недействительный URL.",
    "after" => "Поле :attribute должно быть датой после :date.",
    "alpha" => "Поле :attribute может содержать только буквы.",
    "alpha_dash" => "Поле :attribute может содержать только буквы, цифры и дефис.",
    "alpha_num" => "Поле :attribute может содержать только буквы и цифры.",
    "array" => "Поле :attribute должно быть массивом.",
    "before" => "Поле :attribute должно быть датой перед :date.",
    "between" => [
        "numeric" => "Поле :attribute должно быть между :min и :max.",
        "file" => "Размер :attribute должен быть от :min до :max Килобайт.",
        "string" => "Длина :attribute должна быть от :min до :max символов.",
        "array" => "Поле :attribute должно содержать :min - :max элементов."
    ],
    "confirmed" => "Поле :attribute не совпадает с подтверждением.",
    "date" => "Поле :attribute не является датой.",
    "date_format" => "Поле :attribute не соответствует формату :format.",
    "different" => "Поля :attribute и :other должны различаться.",
    "digits" => "Длина цифрового поля :attribute должна быть :digits.",
    "digits_between" => "Длина цифрового поля :attribute должна быть между :min и :max.",
    "email" => "Поле :attribute должно содержать корректный e-mail адрес.",
    "exists" => "Выбранное значение для поля :attribute некорректно.",
    "image" => "Поле :attribute должно быть изображением.",
    "in" => "Выбранное значение для поля :attribute ошибочно.",
    "integer" => "Поле :attribute должно быть целым числом.",
    "ip" => "Поле :attribute должно быть действительным IP-адресом.",
    "max" => [
        "numeric" => "Поле :attribute должно быть не больше :max.",
        "file" => "Поле :attribute должно быть не больше :max Килобайт.",
        "string" => "Поле :attribute должно быть не длиннее :max символов.",
        "array" => "Поле :attribute должно содержать не более :max элементов."
    ],
    "mimes" => "Поле :attribute должно быть файлом одного из типов: :values.",
    "min" => [
        "numeric" => "Поле :attribute должно быть не менее :min.",
        "file" => "Поле :attribute должно быть не менее :min Килобайт.",
        "string" => "Поле :attribute должно быть не короче :min символов.",
        "array" => "Поле :attribute должно содержать не менее :min элементов."
    ],
    "not_in" => "Выбранное значение для поля :attribute ошибочно.",
    "numeric" => "Поле :attribute должно быть числом.",
    "regex" => "Поле :attribute имеет ошибочный формат.",
    "required" => "Поле :attribute обязательно для заполнения.",
    "required_if" => "Поле :attribute обязательно для заполнения, когда :other равно :value.",
    "required_with" => "Поле :attribute обязательно для заполнения, когда :values указано.",
    "required_without" => "Поле :attribute обязательно для заполнения, когда :values не указано.",
    "required_without_all" => "Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.",
    "same" => "Значение :attribute должно совпадать с :other.",
    "size" => [
        "numeric" => "Поле :attribute должно быть :size.",
        "file" => "Поле :attribute должно быть :size Килобайт.",
        "string" => "Поле :attribute должно быть длиной :size символов.",
        "array" => "Количество элементов в поле :attribute должно быть :size."
    ],
    "unique" => "Такое значение поля :attribute уже существует.",
    "url" => "Поле :attribute должно содержать корректный адрес сайта.",
    "product_id_array" => "Поле :attribute заполнено неверно.",
    'product_id_array_with_values' => 'Поле :attribute заполнено неверно.',
    "product_additional_attributes" => "Дополнительные параметры заполнены неверно.",
    "additional_attribute_variants" => "Поле :attribute не должно содержать пустые значения.",
    'subset' => 'Поле :attribute должно содержать значения из множества: :variants.',
    'phone' => 'Поле :attribute должно содержать корректный телефонный номер. Пример: +7 (897) 0987908.',
    'allowed_ip_list' => 'Некорректный список IP адресов',
    'photo_image_list' => 'Поле :attribute должно содержать файлы типов: :values.',
    'multi_key_exists' => 'Поле :attribute содержит некорректные значения',
    'multi_exists' => 'Поле :attribute содержит некорректные значения',
    'add_remove_model_list_count' => 'Максимальное количество элементов в поле :attribute равно :size',
    'account_password' => 'Поле :attribute должно быть не менее 1 символа.',
    'more_than' => 'Поле :attribute должно быть больше :value.',
    /*
    |--------------------------------------------------------------------------
    | Собственные языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Здесь Вы можете указать собственные сообщения для атрибутов, используя
    | соглашение именования строк "attribute.rule". Это позволяет легко указать
    | свое сообщение для заданного правила атрибута.
    |
    | http://laravel.com/docs/validation#custom-error-messages
    |
    */

    'custom' => [],
    /*
    |--------------------------------------------------------------------------
    | Собственные названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Последующие строки используются для подмены программных имен элементов
    | пользовательского интерфейса на удобочитаемые. Например, вместо имени
    | поля "email" в сообщениях будет выводиться "электронный адрес".
    |
    | Пример использования
    |
    |   'attributes' => array(
    |       'email' => 'электронный адрес',
    |   )
    |
    */

    'attributes' => [
        'created_at' => 'Дата создания',
        'updated_at' => 'Дата редактирования',
        'name' => 'Название',
        'username' => 'Имя пользователя',
        'full_name' => 'ФИО',
        'password' => 'Пароль',
        'new_password' => 'Новый пароль',
        'password_confirmation' => 'Подтверждение пароля',
        'allowed_ips' => 'Разрешённые IP адреса',
        'active' => 'Активность',
        'rules' => 'Правила',
        'admin_role_id' => 'Роль',
        'alias' => 'Псевдоним URL',
        'parent_id' => 'Родитель',
        'type' => 'Тип',
        'position' => 'Позиция',
        'publish' => 'Публикация',
        'header' => 'Заголовок',
        'meta_title' => 'Meta title',
        'meta_description' => 'Meta description',
        'meta_keywords' => 'Meta keywords',
        'content' => 'Содержимое',
        'small_content' => 'Мини-описание для товара в листингах',
        'menu_top' => 'В верхнем меню',
        'scrolled_menu_top' => 'В меню во всплывающей шапке',
        'menu_bottom' => 'В нижнем меню',
        'on_home_page' => 'Отображать на главной странице',
        'category_id' => 'Категория',
        'image_file' => 'Изображение',
        'preview_image_file' => 'Изображение для превью',
        'price' => 'Цена',
        'short_content' => 'Краткое содержимое',
        'not_chosen' => '',
        'on_product_page' => 'Отображать на странице товара',
        'use_in_similar_products' => 'Отображать в блоке "Похожий шкаф" на странице товара',
        'similar_products_name' => 'Название для блока "Похожий шкаф" на странице товара',
        'filter_query' => 'Строка фильтра',
        'manual_product_list_category_id' => 'Категория для фильтра',
        'default_filter_category_id' => 'Категория для фильтра по умолчанию',
        'allowed_values' => 'Разрешённые значения',
        'value' => 'Значение',
        'allowed_value_id' => 'Разрешенное значение',
        'allowed_value_id_list' => 'Список разрешенных значений',
        'top_menu' => 'Отображать в верхнем меню',
        'logo_file' => 'Логотип',
        'content_top' => 'Содержимое вверху страницы',
        'content_bottom' => 'Содержимое внизу страницы',
        'content_for_grid' => 'Содержимое сетки',
        'site_name' => 'Название для сайта',
        'order_number' => 'Номер заказа',
        'order_status' => 'Статус',
        'phone' => 'Телефон',
        'email' => 'E-mail',
        'comment' => 'Комментарий',
        'not_specified' => 'не указано',
        'callback_number' => 'Номер',
        'callback_status' => 'Статус',
        'appropriate_time' => 'Удобное время',
        'in_popular' => 'Отображать в популярных категориях на главной',
        'popular_name' => 'Название для блока Популярные категории на главной странице',
        'banners' => 'Баннеры',
        'link' => 'Ссылка',
        'description' => 'Описание',
        'access_to_mail' => 'Разрешить отправку сообщения',
        'is_mailed' => 'Сообщение отправлено',
        'number' => 'Номер',
        'built_in' => 'Шкаф встроенный?',
        'icon_file' => 'Иконка',
        'address' => 'Адрес',
        'product_gallery_images' => 'Галерея изображений',
        'associated_categories' => 'Связанные категории',
        'answer' => 'Ответ',
        'date_at' => 'Дата',
        'contact_form' => 'Отображать форму обратной связи',

        'similar_products_block_name' => 'Название блока "Похожий товар" на странице товара',
        'no_template_text' => 'Не выводить описание по шаблону',
        'content_header' => 'Блок текста после заголовка',
        'content_header_show' => 'Показывать блок текста после заголовка',
        'product_id' => 'Товар',
        'order_icon_type' => 'Отображать у всех товаров на этой странице иконку "НА ЗАКАЗ" в виде текста, а не картинки',
        'use_reviews_associations' => 'Отображать на этой странице привязанные отзывы',
        'use_reviews_associations_short' => 'Отображать отзывы',

        'use_sort_scheme' => 'Использовать случайный порядок товаров',
        'content_for_submenu' => 'Содержимое для всплывающего подменю',
        'content_for_sidebar' => 'Содержимое для sidebar',
        'hide_regions_in_page' => 'Скрыть блок "В Московской области" на странице',
        'parent_category_id' => 'Родительская категория',
    ],
    'model_attributes' => [
        'order' => [
            'status' => [
                'novel' => 'Новый',
                'processed' => 'Обработан',
                'cancelled' => 'Отменён',
                'executed' => 'Выполнен',
                'returns' => 'Возврат',
                'refusal' => 'Отказ',
            ],
            'product' => 'Товар',
            'form' =>
                [
                    'success' => 'Благодарим за сообщение!<br />Наш представитель свяжется с Вами в ближайшее время.'
                ],
        ],
        'callback' => [
            'status' => [
                'novel' => 'Новый',
                'executed' => 'Выполнен',
            ],
            'type' => 'Тип заявки',
            'callback_type' => [
                'callback' => 'Заказ звонка',
                'measurement' => 'Вызов замерщика',
                'contacts' => 'Контакты',
            ],
            'url_referer' => 'Страница отправки',
            'form' =>
                [
                    'success' => 'Ваше сообщение успешно отправлено. Наш менеджер свяжется с Вами в ближайшее время.'
                ],
        ],
        'banner' => [
            'image' => 'Изображение для баннера',
        ],
        'catalog_product' => [
            'built_in' => [
                '0' => 'Не определено',
                '1' => 'Да',
                '2' => 'Нет',
            ],
        ],
        'catalog_category' => [
            'logo_active_file' => 'Логотип для активного пункта меню',
        ],
    ]
];
