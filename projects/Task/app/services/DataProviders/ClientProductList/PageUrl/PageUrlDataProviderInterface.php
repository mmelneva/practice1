<?php namespace App\Services\DataProviders\ClientProductList\PageUrl;

/**
 * Interface PageUrlDataProviderInterface
 * Data provider to build page url for product pagination.
 *
 * @package App\Services\DataProviders\ClientProductList\PageUrl
 */
interface PageUrlDataProviderInterface
{
    /**
     * Get page url according to received page and sorting way.
     *
     * @param $page
     * @return mixed
     */
    public function getPageUrl($page);
}
