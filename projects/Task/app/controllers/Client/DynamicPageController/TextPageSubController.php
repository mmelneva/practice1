<?php namespace App\Controllers\Client\DynamicPageController;

use App\Models\Node;
use App\Models\TextPage;

class TextPageSubController extends DynamicPageSubController
{
    const PARENT_PAGE_ARTICLES = 506;

    public function getTextPageResponse(Node $node, TextPage $textPage)
    {
        $sidebarArticles = false;
        $metaData = \MetaHelper::metaForObject($textPage, $node->name);
        $children = $this->nodeRepository->getPublishedChildrenContentModels($node->id);
        $breadcrumbs = $this->getBreadcrumbs($node);
        $hideRegionsInPage = $node->hide_regions_in_page;
        $slidebarBlock =  \SettingGetter::get('sidebar_stati');
        if ((($node->parent_id) == self::PARENT_PAGE_ARTICLES) && ($slidebarBlock !='')) {
            $sidebarArticles = true;
        }

        return \View::make('client.text_pages.page')
            ->with('textPage', $textPage)
            ->with('children', $children)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('hideRegionsInPage', $hideRegionsInPage)
            ->with($metaData)
            ->with('sidebarArticles', $sidebarArticles);
    }
}
