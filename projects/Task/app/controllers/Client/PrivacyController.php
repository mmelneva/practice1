<?php
namespace App\Controllers\Client;

use App\Controllers\BaseController;

class PrivacyController extends BaseController
{
    const DEFAULT_PAGE_NAME = 'Политика конфиденциальности';

    public function getPage()
    {
        $breadcrumbs = \Breadcrumbs::init();
        $breadcrumbs->add(self::DEFAULT_PAGE_NAME);
        $metaData = \MetaHelper::metaForName(self::DEFAULT_PAGE_NAME);
        $domainName = \Request::getHost();

        return \View::make('client.privacy_page.page')
            ->with($metaData)
            ->with(compact('breadcrumbs', 'domainName'))
            ->with('hideRegionsInPage', true);
    }
}
