<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => false,
    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => array_get($_ENV, 'URL'),
    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Europe/Moscow',
    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'ru',
    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'ru',
    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => array_get($_ENV, 'SECRET_KEY'),
    'cipher' => MCRYPT_RIJNDAEL_128,
    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Session\CommandsServiceProvider',
        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Encryption\EncryptionServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
        'Illuminate\Log\LogServiceProvider',
        'Illuminate\Mail\MailServiceProvider',
        'Illuminate\Database\MigrationServiceProvider',
        'Illuminate\Pagination\PaginationServiceProvider',
        'Illuminate\Queue\QueueServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Remote\RemoteServiceProvider',
        'Illuminate\Auth\Reminders\ReminderServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider',

        // packages
        'Diol\Fileclip\FileclipServiceProvider',
        'Diol\FileclipExif\FileclipExifServiceProvider',
        'Barryvdh\Elfinder\ElfinderServiceProvider',
        'Barryvdh\Debugbar\ServiceProvider',
        'Diol\LaravelMailer\ServiceProvider',
        'Diol\LaravelErrorSender\ServiceProvider',
        'Diol\LaravelAssets\LaravelAssetsServiceProvider',
        'Diol\LaravelSweetTooltip\ServiceProvider',

        // project
        'App\Services\Providers\Admin\AclServiceProvider',
        'App\Services\Providers\Admin\MenuServiceProvider',
        'App\Services\Providers\FiltersServiceProvider',
        'App\Services\Providers\RepositoriesServiceProvider',
        'App\Services\Providers\FormProcessorsServiceProvider',
        'App\Services\Providers\FormBuilderServiceProvider',
        'App\Services\Providers\ComposersServiceProvider',
        'App\Services\Providers\ValidationServiceProvider',
        'App\Services\Providers\StructureTypesServiceProvider',
        'App\Services\Providers\SeoServiceProvider',
        'App\Services\Providers\BreadcrumbsServiceProvider',
        'App\Services\Providers\ErrorsServiceProvider',
        'App\Services\Providers\StringServiceProvider',
        'App\Services\Providers\HtmlBuilderServiceProvider',
        'App\Services\Providers\SettingsServiceProvider',
        'App\Services\Providers\CatalogServiceProvider',
        'App\Services\Providers\MailsHelperServiceProvider',
        'App\Services\Providers\UrlBuilderServiceProvider',
        'App\Services\Providers\ReviewRenewalServiceProvider',

    ),
    /*
    |--------------------------------------------------------------------------
    | Service Provider Manifest
    |--------------------------------------------------------------------------
    |
    | The service provider manifest is used by Laravel to lazy load service
    | providers which are not needed for each request, as well to keep a
    | list of all of the services. Here, you may set its storage spot.
    |
    */

    'manifest' => storage_path() . '/meta',
    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => array(

        // application
        'App' => 'Illuminate\Support\Facades\App',
        'Artisan' => 'Illuminate\Support\Facades\Artisan',
        'Auth' => 'Illuminate\Support\Facades\Auth',
        'Blade' => 'Illuminate\Support\Facades\Blade',
        'Cache' => 'Illuminate\Support\Facades\Cache',
        'ClassLoader' => 'Illuminate\Support\ClassLoader',
        'Config' => 'Illuminate\Support\Facades\Config',
        'Controller' => 'Illuminate\Routing\Controller',
        'Cookie' => 'Illuminate\Support\Facades\Cookie',
        'Crypt' => 'Illuminate\Support\Facades\Crypt',
        'DB' => 'Illuminate\Support\Facades\DB',
        'Eloquent' => 'Illuminate\Database\Eloquent\Model',
        'Event' => 'Illuminate\Support\Facades\Event',
        'File' => 'Illuminate\Support\Facades\File',
        'Form' => 'Illuminate\Support\Facades\Form',
        'Hash' => 'Illuminate\Support\Facades\Hash',
        'HTML' => 'Illuminate\Support\Facades\HTML',
        'Input' => 'Illuminate\Support\Facades\Input',
        'Lang' => 'Illuminate\Support\Facades\Lang',
        'Log' => 'Illuminate\Support\Facades\Log',
        'Mail' => 'Diol\LaravelMailer\Facade',
        'Paginator' => 'Illuminate\Support\Facades\Paginator',
        'Password' => 'Illuminate\Support\Facades\Password',
        'Queue' => 'Illuminate\Support\Facades\Queue',
        'Redirect' => 'Illuminate\Support\Facades\Redirect',
        'Redis' => 'Illuminate\Support\Facades\Redis',
        'Request' => 'Illuminate\Support\Facades\Request',
        'Response' => 'Illuminate\Support\Facades\Response',
        'Route' => 'Illuminate\Support\Facades\Route',
        'Schema' => 'Illuminate\Support\Facades\Schema',
        'Seeder' => 'Illuminate\Database\Seeder',
        'Session' => 'Illuminate\Support\Facades\Session',
        'SoftDeletingTrait' => 'Illuminate\Database\Eloquent\SoftDeletingTrait',
        'SSH' => 'Illuminate\Support\Facades\SSH',
        'Str' => 'Illuminate\Support\Str',
        'URL' => 'Illuminate\Support\Facades\URL',
        'Validator' => 'Illuminate\Support\Facades\Validator',
        'View' => 'Illuminate\Support\Facades\View',

        // packages
        'Debugbar' => 'Barryvdh\Debugbar\Facade',
        'Asset' => 'Diol\LaravelAssets\AssetFacade',

        // project
        'BaseController' => 'App\Controllers\BaseController',
        'TypeContainer' => 'App\Services\Facades\TypeContainer',
        'MetaHelper' => 'App\Services\Facades\MetaHelper',
        'SettingGetter' => 'App\Services\Facades\SettingGetterFacade',
        'Breadcrumbs' => 'App\Services\Facades\Breadcrumbs',
        'CatalogUrlBuilder' => 'App\Services\Facades\CatalogUrlBuilder',
        'UrlBuilder' => 'App\Services\Facades\UrlBuilder',
        'CatalogPathFinder' => 'App\Services\Facades\CatalogPathFinder',
        'OrderMailsHelper' => 'App\Services\Facades\OrderMailsHelperFacade',
        'ReviewsMailsHelper' => 'App\Services\Facades\ReviewsMailsHelperFacade',
   ),

);
