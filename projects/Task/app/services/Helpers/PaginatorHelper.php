<?php namespace App\Services\Helpers;

/**
 * Class PaginatorHelper
 * @package App\Services\Helpers
 */
class PaginatorHelper
{
    /**
     * Prepare page for current key.
     * It will save or retrieve page from Session by key
     * This method will work only when request exists
     * @param $key
     */
    public function preparePageFor($key)
    {
        $sessionKey = 'pagination.page.' . $key;

        if (!is_null(\Input::get('page'))) {
            \Session::set($sessionKey, \Input::get('page'));
        } elseif (!is_null(\Session::get($sessionKey))) {
            \Paginator::setCurrentPage(\Session::get($sessionKey));
        }
    }

    /**
     * Get items count on page for key.
     * @param $key
     * @param array $variants
     * @return mixed
     */
    public function getOnPage($key, array $variants)
    {
        $sessionKey = 'pagination.on_page.' . $key;

        $onPage = \Input::get('on_page');
        if (is_null($onPage)) {
            $onPage = \Session::get($sessionKey);
        } elseif (in_array($onPage, $variants)) {
            \Session::set($sessionKey, $onPage);
        }

        if (is_null($onPage) || !in_array($onPage, $variants)) {
            $onPage = $variants[0];
        }

        return $onPage;
    }
}
