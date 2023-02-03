<?php namespace Diol\LaravelAssets;

use Illuminate\Support\Facades\Facade;

/**
 * Class AssetFacade
 * @package Diol\LaravelAssets
 */
class AssetFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-assets.asset';
    }
}
