<?php namespace App\Services\DataProviders\ClientProductList\PageUrl;

/**
 * Class SimplePageUrlDataProvider
 * @package App\Services\DataProviders\ClientProductList\PageUrl
 */
class SimplePageUrlDataProvider
{
    /**
     * Get page url by it base url
     * @param $baseUrl
     * @param $page
     * @return string
     */
    public function getPageUrlByBaseUrl($baseUrl, $page)
    {
        $pagePrefix = 'page-';

        $pageUrl = $baseUrl;
        if ($page != 1) {
            $pageUrl .= "/{$pagePrefix}{$page}";
        }

        return $pageUrl;
    }
}
