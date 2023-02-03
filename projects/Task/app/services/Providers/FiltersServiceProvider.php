<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

/**
 * Class FiltersServiceProvider
 * @package App\Services\Providers
 */
class FiltersServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        Route::filter('csrf', 'App\Services\Filters\CsrfFilter');
        Route::filter('no-cache', 'App\Services\Filters\NoCacheFilter');

        Route::filter('auth.admin', 'App\Services\Filters\AuthAdminFilter');
        Route::filter('admin_acl', 'App\Services\Filters\AdminAclFilter');

        \App::before('App\Services\Filters\RedirectsFilter');
        \App::before('App\Services\Filters\RequestFilter');
    }
}
