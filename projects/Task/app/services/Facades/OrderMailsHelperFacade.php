<?php namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class OrderMailsHelperFacade
 * @package  App\Services\Facades
 */
class OrderMailsHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'order_mails_helper';
    }
}
