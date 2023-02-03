<?php namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class CatalogPathFinder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'catalog.catalog_path_finder';
    }
}
