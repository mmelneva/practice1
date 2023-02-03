<?php namespace Diol\LaravelSweetTooltip;

use Illuminate\Support\ServiceProvider as BasicServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceProvider extends BasicServiceProvider
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
        $this->package('diol/laravel-sweet-tooltip', null, __DIR__);

        $this->app['router']->get(
            '/laravel-sweet-tooltip/assets/css',
            [
                'uses' => 'Diol\LaravelSweetTooltip\Controllers\AssetsController@css',
                'as' => 'laravel-sweet-tooltip.assets.css'
            ]
        );

        $this->app['router']->get(
            '/laravel-sweet-tooltip/assets/js',
            [
                'uses' => 'Diol\LaravelSweetTooltip\Controllers\AssetsController@js',
                'as' => 'laravel-sweet-tooltip.assets.js'
            ]
        );


        if ($this->app['config']->get('laravel-sweet-tooltip::auto_inject')) {
            $this->app['router']->after(
                function (Request $request, Response $response) {
                    $content = $response->getContent();
                    $pos = strripos($content, '</body>');
                    if (false !== $pos) {
                        $injectContent = $this->app['view']->make('laravel-sweet-tooltip::default')->render();
                        $content = substr($content, 0, $pos) . $injectContent . substr($content, $pos);
                        $response->setContent($content);
                    }
                }
            );
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
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
}
