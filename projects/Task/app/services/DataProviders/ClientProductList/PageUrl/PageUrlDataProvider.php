<?php namespace App\Services\DataProviders\ClientProductList\PageUrl;

/**
 * Class CategoryPageUrlDataProvider
 * Data provider to get page url for pagination.
 *
 * @package App\Services\DataProviders\ClientProductList\PageUrl
 */
abstract class PageUrlDataProvider extends SimplePageUrlDataProvider implements PageUrlDataProviderInterface
{
    abstract public function getPageUrl($page);
}
