<?php namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class MetaHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'seo.meta_helper';
    }
}
