<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blind Copy "To" Address
    |--------------------------------------------------------------------------
    |
    | Message also will be sent on this address for defined environments.
    |
    */

    'blind_copy' => [
        'address' => array_get(
            $_ENV,
            'MAIL_BLIND_COPY_ADDRESS',
            'diol.test@gmail.com, diol-test@yandex.ru, diol-test@mail.ru, diol-test@lenta.ru'
        ),
        'environments' => ['production'], // blind copies of email will be send for these environments.
    ],

    /*
    |--------------------------------------------------------------------------
    | Substituted "To" Address
    |--------------------------------------------------------------------------
    |
    | All messages will be sent on this address for defined environments.
    |
    */

    'substituted_to' => [
        'address' => array_get(
            $_ENV,
            'MAIL_SUBSTITUTED_TO_ADDRESS',
            'diol-test@yandex.ru'
        ),
        'environments' => ['local'], // recipient will be substitute for environments.
    ],

    /*
    |--------------------------------------------------------------------------
    | "Return path" Address
    |--------------------------------------------------------------------------
    |
    | Unreached emails wil be sent to this address.
    |
    */

    'return_path' => array_get(
        $_ENV,
        'MAIL_RETURN_PATH',
        'diol.tech.info@gmail.com'
    ),

    /*
    |--------------------------------------------------------------------------
    | "Reply To" Address
    |--------------------------------------------------------------------------
    |
    |  This address will be set by default unless it has been installed manually.
    |
    */

    'reply_to' => [
        'address' => array_get(
            $_ENV,
            'MAIL_REPLY_TO_ADDRESS',
            Config::get('mail.reply_to.address')
        ),
        'name' => array_get(
            $_ENV,
            'MAIL_REPLY_TO_NAME',
            Config::get('mail.reply_to.name')
        ),
    ],
];
