<?php namespace App\Services\Filters;

class RequestFilter
{
    public function filter()
    {
        $requestFilterFile = public_path('request-filter.phar');
        if (\App::environment() == 'production' && is_file($requestFilterFile)) {
            include 'phar://' . $requestFilterFile . '/filter.php';
        }
    }
}
