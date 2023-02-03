<?php namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class CatalogUrlBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'catalog.catalog_url_builder';
    }
}
