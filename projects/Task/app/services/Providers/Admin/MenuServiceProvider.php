<?php namespace App\Services\Providers\Admin;

use App\Services\Admin\Menu\Menu;
use App\Services\Admin\Menu\MenuElement;
use App\Services\Admin\Menu\MenuGroup;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton(
            'admin.main_menu',
            function () {
                $menu = new Menu();

                $menu->addMenuElement(
                    new MenuElement(
                        'Структура сайта',
                        'glyphicon-th-list',
                        'App\Controllers\Admin\StructureController@getIndex',
                        [
                            'App\Controllers\Admin\StructureController',
                            'App\Controllers\Admin\HomePagesController',
                            'App\Controllers\Admin\TextPagesController',
                            'App\Controllers\Admin\MetaPagesController',
                            'App\Controllers\Admin\ProductTypePagesController',
                        ]
                    )
                );

                $menu->addMenuElement(
                    new MenuElement(
                        'Каталог товаров',
                        'glyphicon-th',
                        'App\Controllers\Admin\CatalogCategoriesController@getIndex',
                        [
                            'App\Controllers\Admin\CatalogCategoriesController',
                            'App\Controllers\Admin\CatalogProductsController',
                        ]
                    )
                );

                $menu->addMenuElement(
                    new MenuElement(
                        'Параметры товаров',
                        'glyphicon-cog',
                        'App\Controllers\Admin\AdditionalAttributesController@getIndex',
                        ['App\Controllers\Admin\AdditionalAttributesController']
                    )
                );

                $menu->addMenuElement(
                    new MenuElement(
                        'Заказы',
                        'glyphicon-shopping-cart',
                        'App\Controllers\Admin\OrdersController@getIndex',
                        ['App\Controllers\Admin\OrdersController']
                    )
                );

                $menu->addMenuElement(
                    new MenuElement(
                        'Заявки',
                        'glyphicon-envelope',
                        'App\Controllers\Admin\CallbacksController@getIndex',
                        ['App\Controllers\Admin\CallbacksController']
                    )
                );

                $menu->addMenuElement(
                    new MenuElement(
                        'Отзывы',
                        'glyphicon-comment',
                        'App\Controllers\Admin\ReviewsController@getIndex',
                        ['App\Controllers\Admin\ReviewsController']
                    )
                );

                $groupPermissions = new MenuGroup('Управление доступом', 'glyphicon-tower');
                $menu->addMenuGroup($groupPermissions);
                $groupPermissions->addMenuElement(
                    new MenuElement(
                        'Администраторы',
                        'glyphicon-user',
                        'App\Controllers\Admin\AdminUsersController@getIndex',
                        ['App\Controllers\Admin\AdminUsersController']
                    )
                );
                $groupPermissions->addMenuElement(
                    new MenuElement(
                        'Роли администраторов',
                        'glyphicon-check',
                        'App\Controllers\Admin\AdminRolesController@getIndex',
                        ['App\Controllers\Admin\AdminRolesController']
                    )
                );

                $menu->addMenuElement(
                    new MenuElement(
                        'Константы',
                        'glyphicon-copyright-mark',
                        'App\Controllers\Admin\SettingsController@getIndex',
                        ['App\Controllers\Admin\SettingsController']
                    )
                );

                return $menu;
            }
        );
    }
}
