<?php namespace Diol\LaravelAssets;

use Illuminate\Support\ServiceProvider;

class LaravelAssetsServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'laravel-assets.asset-manager',
            function () {
                return new AssetManager(
                    \Config::get('laravel-assets::groups'),
                    \Config::get('laravel-assets::filters')
                );
            }
        );

        $this->app->singleton(
            'laravel-assets.asset',
            function () {
                return new Asset(
                    $this->app->make('laravel-assets.asset-manager'),
                    \Config::get('laravel-assets::merge_environments')
                );
            }
        );

        $this->app->singleton(
            'laravel-assets.commands.compile',
            function () {
                return new AssetCompileCommand(
                    $this->app->make('laravel-assets.asset-manager'),
                    \Config::get('laravel-assets::merge_environments')
                );
            }
        );

        $this->commands(['laravel-assets.commands.compile']);
    }

    public function boot()
    {
        $this->package('diol/laravel-assets');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-assets.asset'];
    }
}
