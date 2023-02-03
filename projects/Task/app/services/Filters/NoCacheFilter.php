<?php namespace App\Services\Filters;

/**
 * Class NoCacheFilter
 * @package App\Services\Filters
 */
class NoCacheFilter
{
    public function filter($route, $request, $response)
    {
        // No caching for pages
        $response->header("Pragma", "no-cache");
        $response->header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
    }
}
