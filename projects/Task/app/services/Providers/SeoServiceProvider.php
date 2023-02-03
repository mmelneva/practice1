<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'seo.meta_helper',
            function () {
                $metaHelper = $this->app->make('App\Services\Seo\MetaHelper');
                return $metaHelper;
            }
        );
    }


}
