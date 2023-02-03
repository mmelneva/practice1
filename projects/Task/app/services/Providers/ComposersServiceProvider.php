<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

class ComposersServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        \View::composers(
            [
                // Admin composers
                'App\Services\Composers\AdminAlertComposer' => 'admin.layouts._alerts',
                'App\Services\Composers\CurrentAdminUserComposer' => 'admin.layouts._top_nav',
                'App\Services\Composers\AdminMainMenuComposer' => 'admin.layouts._main_menu',

                // Client composers
                'App\Services\Composers\ClientTopMenuComposer' => 'client.layouts._header_fix',
//                'App\Services\Composers\ClientScrollTopMenuComposer' => 'client.layouts._header_scroll',
//                'App\Services\Composers\ClientBottomMenuComposer' => 'client.layouts._footer',
                'App\Services\Composers\ClientPhoneNumbersComposer' => 'client.layouts.default',
                'App\Services\Composers\ClientCatalogMenuComposer' => 'client.layouts._catalog_menu',
                'App\Services\Composers\ClientMeasurementLinkComposer' => 'client.layouts._measurement_link',
            ]
        );
    }
}
