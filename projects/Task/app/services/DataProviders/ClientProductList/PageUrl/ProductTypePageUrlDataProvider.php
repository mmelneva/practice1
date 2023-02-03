<?php namespace App\Services\DataProviders\ClientProductList\PageUrl;

use App\Models\Node;

/**
 * Class ProductTypePageUrlDataProvider
 * Data provider to get page url for pagination for product type page.
 *
 * @package App\Services\DataProviders\ClientProductList\PageUrl
 */
class ProductTypePageUrlDataProvider extends PageUrlDataProvider
{
    /**
     * @var Node
     */
    private $node;
    /**
     * @var string
     */
    private $sort;

    /**
     * @param Node $node - node of product type page.
     * @param $sort
     */
    public function __construct(Node $node, $sort = null)
    {
        $this->node = $node;
        $this->sort = $sort;
    }

    public function getPageUrl($page)
    {
        $pageUrl = $this->getPageUrlByBaseUrl(\UrlBuilder::getUrl($this->node), $page);

        $pageQuery = http_build_query(['sort' => $this->sort]);
        if (!empty($pageQuery)) {
            $pageUrl .= '?' . $pageQuery;
        }

        return $pageUrl;
    }
}
