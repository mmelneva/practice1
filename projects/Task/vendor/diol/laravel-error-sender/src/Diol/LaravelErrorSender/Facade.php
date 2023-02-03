<?php namespace Diol\LaravelErrorSender;

/**
 * Class Facade
 * @package Diol\LaravelErrorSender
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'error_sender';
    }
}
