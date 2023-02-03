<?php

return [
    'alert_mail' => [
        'address' => array_get(
            $_ENV,
            'MAIL_ALERT_ADDRESS',
            [
                'alex@diol-it.ru',
                'diol.tech.info@gmail.com',
                'diol.test@gmail.com',
                'diol-test@yandex.ru',
                'diol-test@mail.ru',
                'diol-test@lenta.ru',
            ]
        ),
        'subject' => 'Критическая ошибка на сайте ' . \Request::root(),
        'sending_interval' => 60,
    ],
    'error_dump' => [
        'file_name' => 'full-dump',
    ],
    'ignore_exceptions' => [
    ]
];
