<?php namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class UrlBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'url_builder';
    }
}
