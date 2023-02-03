<?php namespace App\Services\Filters;

use Illuminate\Session\TokenMismatchException;

/**
 * Class CsrfFilter
 * @package App\Services\Filters
 */
class CsrfFilter
{
    public function filter()
    {
        if (\Session::token() != \Input::get('_token')) {
            throw new TokenMismatchException;
        }
    }
}
