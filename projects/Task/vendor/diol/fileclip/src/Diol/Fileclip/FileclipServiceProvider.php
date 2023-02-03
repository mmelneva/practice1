<?php namespace Diol\Fileclip;

use Diol\Fileclip\Console\GetMissingOriginalsCommand;
use Diol\Fileclip\Console\GetModelsWithAttachments;
use Diol\Fileclip\Console\UpdateAttachmentsCommand;
use Diol\Fileclip\InputFileWrapper\WrapperFactory\HttpFileFactory;
use Diol\Fileclip\InputFileWrapper\WrapperFactory\LocalSystemFileFactory;
use Diol\Fileclip\InputFileWrapper\WrapperFactory\SymfonyUploadedFileFactory;
use Diol\Fileclip\InputFileWrapper\WrapperFactoryCollector;
use Diol\Fileclip\Uploader\NameGenerator\ThroughNumberingNameGenerator;
use Diol\Fileclip\Uploader\NameGenerator\OriginalNameGenerator;
use Diol\Fileclip\Uploader\NameGenerator\RandomNameGenerator;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\ServiceProvider;
use Imagine\Gd\Imagine as GdImagine;
use Imagine\Imagick\Imagine as ImagickImagine;
use Illuminate\Support\Facades\Config;

/**
 * Class FileclipServiceProvider
 * Service provider to integrate this package into laravel application
 * @package Diol\Fileclip
 */
class FileclipServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('diol/fileclip');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerInputFileWrapperFactory();
        $this->registerImagine();
        $this->registerNameGenerator();
        $this->registerHttpClient();
        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    /**
     * Register file wrappers factory
     */
    private function registerInputFileWrapperFactory()
    {
        $this->app->singleton(
            'fileclip::wrapper_factory_collector',
            function () {
                $wrapperFactoryCollector = new WrapperFactoryCollector();
                $wrapperFactoryCollector->addWrapperFactory(new LocalSystemFileFactory());
                $wrapperFactoryCollector->addWrapperFactory(new SymfonyUploadedFileFactory());
                $wrapperFactoryCollector->addWrapperFactory(new HttpFileFactory());

                return $wrapperFactoryCollector;
            }
        );
    }

    /**
     * Register imagine instance
     */
    private function registerImagine()
    {
        $this->app->singleton(
            'fileclip::imagine',
            function () {
                $imagineDrivers = [
                    'gd' => function () {
                        return new GdImagine();
                    },
                    'imagick' => function () {
                        return new ImagickImagine();
                    },
                    'auto' => function () use (&$imagineDrivers) {
                        return $imagineDrivers[extension_loaded('imagick') ? 'imagick' : 'gd']();
                    }
                ];
                $imagineDriverKey = Config::get('fileclip::imagine_driver', 'auto');

                if (!array_key_exists($imagineDriverKey, $imagineDrivers)) {
                    throw new \DomainException("Unknown imagine driver: {$imagineDriverKey}");
                }

                return $imagineDrivers[$imagineDriverKey]();
            }
        );
    }

    /**
     * Register name generator (generates names for uploaded files)
     */
    private function registerNameGenerator()
    {
        $this->app->singleton(
            'fileclip::name_generator',
            function () {
                $nameGenerators = [
                    'through_numbering' => function () {
                        return new ThroughNumberingNameGenerator(
                            Config::get('fileclip::filename_prefix')
                        );
                    },
                    'original' => function () {
                        return new OriginalNameGenerator();
                    },
                    'random' => function () {
                        return new RandomNameGenerator();
                    }
                ];
                $nameGeneratorKey = Config::get('fileclip::name_generator', 'through_numbering');

                if (!array_key_exists($nameGeneratorKey, $nameGenerators)) {
                    throw new \DomainException("Unknown name generator: {$nameGeneratorKey}");
                }

                return $nameGenerators[$nameGeneratorKey]();
            }
        );
    }

    /**
     * Register http client.
     */
    private function registerHttpClient()
    {
        $this->app->singleton(
            'fileclip::http_client',
            function () {
                $httpClient = new GuzzleHttpClient();
                $httpClient->setDefaultOption('headers/User-Agent', 'Fileclip');
                $httpClient->setDefaultOption('connect_timeout', 10);
                $httpClient->setDefaultOption('timeout', 10);
                $httpClient->setDefaultOption('allow_redirects', true);
                $httpClient->setDefaultOption('exceptions', false);
                $httpClient->setDefaultOption('verify', false);

                return $httpClient;
            }
        );
    }

    /**
     * Register available commands.
     */
    private function registerCommands()
    {
        $this->app['command.fileclip.update-attachments'] = $this->app->share(
            function () {
                return new UpdateAttachmentsCommand();
            }
        );

        $this->app['command.fileclip.get-missing-originals'] = $this->app->share(
            function () {
                return new GetMissingOriginalsCommand();
            }
        );

        $this->app['command.fileclip.get-models-with-attachments'] = $this->app->share(
            function () {
                return new GetModelsWithAttachments();
            }
        );

        $this->commands([
            'command.fileclip.update-attachments',
            'command.fileclip.get-missing-originals',
            'command.fileclip.get-models-with-attachments',
        ]);
    }
}
