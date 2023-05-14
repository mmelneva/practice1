<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::group(
    ['prefix' => 'cc'],
    function () {
        // Login and logout
        Route::get('login', ['as' => 'cc.login', 'uses' => 'App\Controllers\Admin\SessionsController@create']);
        Route::post('login', ['uses' => 'App\Controllers\Admin\SessionsController@store']);
        Route::delete('logout', ['as' => 'cc.logout', 'uses' => 'App\Controllers\Admin\SessionsController@destroy']);

        Route::group(
            ['before' => ['auth.admin']],
            function () {
                Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showIndex');
                Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
                Route::get('elfinder/tinymce', 'Barryvdh\Elfinder\ElfinderController@showTinyMCE4');

                Route::get('acl-login-route', 'App\Controllers\Admin\AclLoginRouterController@getRoute');
                Route::group(
                    ['before' => ['admin_acl']],
                    function () {
                        // Admin users
                        Route::controller('admin-users', 'App\Controllers\Admin\AdminUsersController');
                        Route::controller('admin-roles', 'App\Controllers\Admin\AdminRolesController');

                        // Site structure
                        Route::controller('structure', 'App\Controllers\Admin\StructureController');
                        Route::get(
                            '/',
                            ['as' => 'cc.home', 'uses' => 'App\Controllers\Admin\StructureController@getIndex']
                        );
                        Route::controller('home-pages', 'App\Controllers\Admin\HomePagesController');
                        Route::controller('text-pages', 'App\Controllers\Admin\TextPagesController');
                        Route::controller('meta-pages', 'App\Controllers\Admin\MetaPagesController');
                        Route::controller('product-type-pages', 'App\Controllers\Admin\ProductTypePagesController');

                        // Catalog
                        Route::controller('catalog-categories', 'App\Controllers\Admin\CatalogCategoriesController');
                        Route::controller('catalog-products', 'App\Controllers\Admin\CatalogProductsController');

                        // Catalog product gallery
                        Route::controller('product-images', 'App\Controllers\Admin\CatalogProductGalleryController');

                        // Catalog product attributes
                        Route::controller(
                            'catalog_product_attributes',
                            'App\Controllers\Admin\AdditionalAttributesController'
                        );

                        // Settings
                        Route::controller('settings', 'App\Controllers\Admin\SettingsController');

                        // Orders
                        Route::controller('orders', 'App\Controllers\Admin\OrdersController');

                        //Callbacks
                        Route::controller('callbacks', 'App\Controllers\Admin\CallbacksController');

                        //Reviews
                        Route::controller('reviews', 'App\Controllers\Admin\ReviewsController', [
                            'getEdit' => 'cc.reviews.edit',
                            'getIndex' => 'cc.reviews.index'
                        ]);
                    }
                );

                // all others should show 404 page for admin
                Route::get(
                    '/{url}',
                    function () {
                        \App::abort(404, 'Unknown route');
                    }
                )->where('url', '.*');
            }
        );
    }
);


Route::group(
    ['after' => ['no-cache']],
    function () {
        Route::get('/', ['as' => 'home', 'uses' => 'App\Controllers\Client\HomePagesController@getPage']);
        Route::get('404', ['as' => '404', 'uses' => 'App\Controllers\Client\ErrorsController@getError404']);
        //Privacy page
        Route::get('privacy', ['as' => 'privacy', 'uses' => 'App\Controllers\Client\PrivacyController@getPage']);

        // Full catalog - only for authorized users
        Route::get(
            'cat/{page?}',
            ['as' => 'full_catalog', 'uses' => 'App\Controllers\Client\CatalogController@getIndex']
        )->where('page', '^page-([1-9]\d*)$');

        // Catalog
        Route::get(
            'cat/{catalogQuery}',
            ['as' => 'catalog', 'uses' => 'App\Controllers\Client\CatalogController@getShow']
        )->where('catalogQuery', '.*');
        
        // Catalog
	  Route::controller('catalog-categories', 'App\Controllers\Client\CatalogCategoriesController');

        // Proxy route for filter
        Route::get(
            'filter-proxy',
            ['as' => 'filter_proxy', 'uses' => 'App\Controllers\Client\FilterProxyController@redirectToFilterUrl']
        );

        // Order
        Route::group(
            ['prefix' => 'order', 'namespace' => 'App\Controllers\Client'],
            function () {
                Route::post(
                    'store',
                    [
                        'as' => 'order.store',
                        'uses' => 'OrdersController@postStore',
                    ]
                );
            }
        );

        // ProductPopupInfo
        Route::controller(
            'product_popup_info',
            'App\Controllers\Client\ProductPopupInfoController',
            [
                'getProductPopupInfoBlock' => 'product_popup_info.product_block',
            ]
        );

        // Callback
        Route::controller(
            'callback',
            'App\Controllers\Client\CallbackController',
            [
                'postStore' => 'callback.store',
            ]
        );

        // Site map
        Route::get('/map', ['as' => 'site_map', 'uses' => 'App\Controllers\Client\SiteMapController@getSiteMapPage']);

        // Measurement
        Route::controller(
            'measurement',
            'App\Controllers\Client\MeasurementPagesController',
            [
                'getIndex' => 'measurement',
            ]
        );

        //Reviews
        Route::get(
            'reviews/{page?}',
            ['as' => 'reviews', 'uses' => 'App\Controllers\Client\ReviewsPagesController@getPage']
        )->where('page', '^page-[0-9]+$');
        Route::controller('reviews', 'App\Controllers\Client\ReviewsPagesController', ['postStore' => 'reviews.store']);


        Route::any('/{url}', ['as' => 'dynamic_page', 'uses' => 'App\Controllers\Client\DynamicPageController@getShow'])
            ->where('url', '.*');
    }
);

Blade::extend(function($value)
{
        return preg_replace('/(\s*)@(break|continue)(\s*)/', '$1<?php $2; ?>$3', $value);
});