<?php namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ReviewsMailsHelperFacade
 * @package App\Services\Facades
 */
class ReviewsMailsHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'reviews_mails_helper';
    }
}
