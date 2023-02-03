<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
    |
    */

    'driver' => array_get($_ENV, 'MAIL_DRIVER', 'mail'),
    /*
    |--------------------------------------------------------------------------
    | SMTP Host Address
    |--------------------------------------------------------------------------
    |
    | Here you may provide the host address of the SMTP server used by your
    | applications. A default option is provided that is compatible with
    | the Mailgun mail service which will provide reliable deliveries.
    |
    */

    'host' => array_get($_ENV, 'MAIL_HOST'),
    /*
    |--------------------------------------------------------------------------
    | SMTP Host Port
    |--------------------------------------------------------------------------
    |
    | This is the SMTP port used by your application to deliver e-mails to
    | users of the application. Like the host we have set this value to
    | stay compatible with the Mailgun e-mail application by default.
    |
    */

    'port' => array_get($_ENV, 'MAIL_PORT', 587),
    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a namnoreply@tri-mamonta.rue and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => array(
        'address' => SettingGetter::get('mail.from.address') ?: array_get($_ENV, 'MAIL_FROM_ADDRESS', 'noreply@'. \Config::get('settings.site_name')),
        'name' => SettingGetter::get('mail.from.name') ?: array_get($_ENV, 'MAIL_FROM_NAME', \Config::get('settings.site_name')),
    ),

    'reply_to' => array(
        'address' => SettingGetter::get('mail.reply_to.address'),
    ),

    /*
    |--------------------------------------------------------------------------
    | Default "To" Address
    |--------------------------------------------------------------------------
    |
    | Replaced address of recipient, actual for not production environment.
    |
    */

    'substituted_to' => array(
        'address' => array_get($_ENV, 'MAIL_SUBSTITUTED_TO_ADDRESS', 'diol.test@gmail.com'),
        'name' => null
    ),
    /*
    |--------------------------------------------------------------------------
    | Blind Copy "To" Address
    |--------------------------------------------------------------------------
    |
    | With production environment, message also will be sent on this address.

     |
    */
    'blind_copy' => array(
        'address' => array_get($_ENV, 'MAIL_BLIND_COPY_ADDRESS',
            'diol.test@gmail.com,diol-test@yandex.ru,diol-test@mail.ru,diol-test@lenta.ru'),
        'name' => null
    ),

    /*
    |--------------------------------------------------------------------------
    | Alert Copy "To" Address
    |--------------------------------------------------------------------------
    |
    | With production environment, message also will be sent on this address when application crash.

     |
    */
    'alert' => array(
        'address' => array_get($_ENV, 'MAIL_ALERT_ADDRESS', 'diol-test@yandex.ru,alex@diol-it.ru,diol.tech.info@gmail.com'),
        'name' => null
    ),

    /*
    |--------------------------------------------------------------------------
    | Mail for feedback
    |--------------------------------------------------------------------------
    |
    | If field in admin constants is empty or emails in it are not valid, message will be sent to this address.

     |
    */
    'feedback' => array(
        'address' => 'lit-mebel@mail.ru',
    ),
    'reviews' => array(
        'address' => 'lit-mebel@mail.ru',
    ),

    /*
    |--------------------------------------------------------------------------
    | E-Mail Encryption Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encryption protocol that should be used when
    | the application send e-mail messages. A sensible default using the
    | transport layer security protocol should provide great security.
    |
    */

    'encryption' => 'tls',
    /*
    |--------------------------------------------------------------------------
    | SMTP Server Username
    |--------------------------------------------------------------------------
    |
    | If your SMTP server requires a username for authentication, you should
    | set it here. This will get used to authenticate with your server on
    | connection. You may also set the "password" value below this one.
    |
    */

    'username' => array_get($_ENV, 'MAIL_USERNAME'),
    /*
    |--------------------------------------------------------------------------
    | SMTP Server Password
    |--------------------------------------------------------------------------
    |
    | Here you may set the password required by your SMTP server to send out
    | messages from your application. This will be given to the server on
    | connection so that the application will be able to send messages.
    |
    */

    'password' => array_get($_ENV, 'MAIL_PASSWORD'),
    /*
    |--------------------------------------------------------------------------
    | Sendmail System Path
    |--------------------------------------------------------------------------
    |
    | When using the "sendmail" driver to send e-mails, we will need to know
    | the path to where Sendmail lives on this server. A default path has
    | been provided here, which will work well on most of your systems.
    |
    */

    'sendmail' => '/usr/sbin/sendmail -bs',
    /*
    |--------------------------------------------------------------------------
    | Mail "Pretend"
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, e-mail will not actually be sent over the
    | web and will instead be written to your application's logs files so
    | you may inspect the message. This is great for local development.
    |
    */

    'pretend' => false,
);
