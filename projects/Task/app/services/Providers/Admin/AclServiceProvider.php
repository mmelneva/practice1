<?php namespace App\Services\Providers\Admin;

use Diol\Laracl\Acl;
use Diol\Laracl\ResourceRule;
use Illuminate\Support\ServiceProvider;

/**
 * Class AclServiceProvider
 * @package App\Services\Providers\Admin
 */
class AclServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton(
            'admin_acl',
            function () {
                $acl = new Acl();

                $acl->addResourceRule(
                    'SiteStructure',
                    new ResourceRule(
                        [
                            'App\Controllers\Admin\StructureController',
                            'App\Controllers\Admin\HomePagesController',
                            'App\Controllers\Admin\TextPagesController',
                            'App\Controllers\Admin\MetaPagesController',
                            'App\Controllers\Admin\ProductTypePagesController',
                        ],
                        'Структура сайта'
                    )
                );

                $acl->addResourceRule(
                    'Catalog',
                    new ResourceRule(
                        [
                            'App\Controllers\Admin\CatalogCategoriesController',
                            'App\Controllers\Admin\CatalogProductsController',
                            'App\Controllers\Admin\AdditionalAttributesController',
                            'App\Controllers\Admin\CatalogProductGalleryController'
                        ],
                        'Каталог товаров'
                    )
                );

                $acl->addResourceRule(
                    'Users',
                    new ResourceRule(
                        [
                            'App\Controllers\Admin\AdminUsersController',
                            'App\Controllers\Admin\AdminRolesController',
                        ],
                        'Управление пользователями'
                    )
                );

                $acl->addResourceRule(
                    'Settings',
                    new ResourceRule(
                        'App\Controllers\Admin\SettingsController',
                        'Константы'
                    )
                );

                $acl->addResourceRule(
                    'Orders',
                    new ResourceRule(
                        ['App\Controllers\Admin\OrdersController'],
                        'Заказы'
                    )
                );

                $acl->addResourceRule(
                    'Callbacks',
                    new ResourceRule(
                        ['App\Controllers\Admin\CallbacksController'],
                        'Заявки'
                    )
                );

                $acl->addResourceRule(
                    'Reviews',
                    new ResourceRule(
                        'App\Controllers\Admin\ReviewsController',
                        'Отзывы'
                    )
                );

                return $acl;
            }
        );
    }
}
