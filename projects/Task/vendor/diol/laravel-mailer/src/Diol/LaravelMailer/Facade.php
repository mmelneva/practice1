<?php namespace Diol\LaravelMailer;

/**
 * Class Facade
 * @package  App\Service\Mail
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'mailer';
    }
}
