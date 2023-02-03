<?php namespace Diol\LaravelErrorSender;

use Illuminate\Cache\FileStore as CacheFileStore;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Exception\WhoopsDisplayer;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @inheritdoc
     */
    protected $defer = false;

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->package('diol/laravel-error-sender');
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerDebugDisplayer();

        $this->app->bind(
            'Diol\LaravelErrorSender\ErrorSender',
            function ($app) {
                return $app['error_sender'];
            }
        );

        $this->app->bindShared(
            'error_sender',
            function () {
                $errorSender = new ErrorSender;
                $errorSender->setExceptionStorage(
                    new ExceptionStorage(
                        new CacheRepository(
                            new CacheFileStore(
                                $this->app['files'],
                                storage_path('laravel-error-sender/cache')
                            )
                        ),
                        \Config::get('laravel-error-sender::alert_mail.sending_interval')
                    )
                );
                return $errorSender;
            }
        );
    }

    /**
     * Register the Whoops exception displayer.
     *
     * @return void
     */
    protected function registerDebugDisplayer()
    {
        $this->registerWhoops();

        $this->app->bindShared('errors_sender.exception.debug', function ($app) {
            return new WhoopsDisplayer($app['errors_sender.whoops'], false);
        });
    }

    /**
     * Register the Whoops error display service.
     *
     * @return void
     */
    protected function registerWhoops()
    {
        $this->registerWhoopsHandler();

        $this->app->bindShared('errors_sender.whoops', function ($app) {
            // We will instruct Whoops to not exit after it displays the exception as it
            // will otherwise run out before we can do anything else. We just want to
            // let the framework go ahead and finish a request on this end instead.
            with($whoops = new Run)->allowQuit(false);

            $whoops->writeToOutput(false);

            return $whoops->pushHandler($app['errors_sender.whoops.handler']);
        });
    }

    /**
     * Register the Whoops handler for the request.
     *
     * @return void
     */
    protected function registerWhoopsHandler()
    {
        $this->app->bindShared('errors_sender.whoops.handler', function () {
            $handler = new PrettyPageHandler;
            $handler->setEditor('sublime');
            $handler->handleUnconditionally(true);

            return $handler;
        });
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return array();
    }
}
