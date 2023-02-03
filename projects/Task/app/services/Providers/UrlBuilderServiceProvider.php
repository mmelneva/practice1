<?php namespace App\Services\Providers;

use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\Node;
use App\Services\UrlBuilder\UrlBuilder;
use Illuminate\Support\ServiceProvider;

class UrlBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'url_builder',
            function () {
                $urlBuilder = new UrlBuilder();

                $urlBuilder->add(
                    function ($object) {
                        return $object instanceof Node;
                    },
                    function ($object) {
                        return \TypeContainer::getClientUrl($object);
                    }
                );

                $urlBuilder->add(
                    function ($object) {
                        return ($object instanceof CatalogCategory || $object instanceof CatalogProduct);
                    },
                    function ($object) {
                        return \CatalogUrlBuilder::getCatalogUrl($object);
                    }
                );

                return $urlBuilder;
            }
        );
    }
}
