<?php namespace App\Services\Providers;

use App\Models\Node;
use App\Services\StructureTypes\RepositoryAssociation;
use App\Services\StructureTypes\Type;
use App\Services\StructureTypes\TypeContainer;
use Illuminate\Support\ServiceProvider;

class StructureTypesServiceProvider extends ServiceProvider
{
    const REPO_HOME_PAGE = 'home_page_repo';
    const REPO_TEXT_PAGE = 'text_page_repo';
    const REPO_ERROR_404 = 'error404_repo';
    const REPO_PRODUCT_TYPE_PAGE = 'product_type_page_repo';
    const REPO_MAP_PAGE = 'map_page_repo';
    const REPO_MEASUREMENT_PAGE = 'measurement_page_repo';
    const REPO_REVIEWS_PAGE = 'reviews_page_repo';

    const TYPE_HOME_PAGE = 'home_page';
    const TYPE_TEXT_PAGE = 'text_page';
    const TYPE_ERROR_404 = 'error404';
    const TYPE_PRODUCT_TYPE_PAGE = 'product_type_page';
    const TYPE_MAP_PAGE = 'map_page';
    const TYPE_MEASUREMENT_PAGE = 'measurement_page';
    const TYPE_REVIEWS_PAGE = 'reviews_page';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'structure_types.types',
            function () {
                $typeContainer = new TypeContainer(
                    $this->app->make('App\Services\Repositories\Node\NodeRepositoryInterface')
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_HOME_PAGE,
                    new RepositoryAssociation(
                        $this->app->make('App\Services\Repositories\HomePage\HomePageRepositoryInterface'),
                        function (Node $node) {
                            return action('App\Controllers\Admin\HomePagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_TEXT_PAGE,
                    new RepositoryAssociation(
                        $this->app->make('App\Services\Repositories\TextPage\TextPageRepositoryInterface'),
                        function (Node $node) {
                            return action('App\Controllers\Admin\TextPagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_ERROR_404,
                    new RepositoryAssociation(
                        $this->app->make('App\Services\Repositories\MetaPage\MetaPageRepositoryInterface'),
                        function (Node $node) {
                            return action('App\Controllers\Admin\MetaPagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_PRODUCT_TYPE_PAGE,
                    new RepositoryAssociation(
                        $this->app->make(
                            'App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface'
                        ),
                        function (Node $node) {
                            return action('App\Controllers\Admin\ProductTypePagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_MAP_PAGE,
                    new RepositoryAssociation(
                        $this->app->make('App\Services\Repositories\MetaPage\MetaPageRepositoryInterface'),
                        function (Node $node) {
                            return action('App\Controllers\Admin\MetaPagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_MEASUREMENT_PAGE,
                    new RepositoryAssociation(
                        $this->app->make('App\Services\Repositories\MetaPage\MetaPageRepositoryInterface'),
                        function (Node $node) {
                            return action('App\Controllers\Admin\MetaPagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addRepositoryAssociation(
                    self::REPO_REVIEWS_PAGE,
                    new RepositoryAssociation(
                        $this->app->make('App\Services\Repositories\MetaPage\MetaPageRepositoryInterface'),
                        function (Node $node) {
                            return action('App\Controllers\Admin\MetaPagesController@getEdit', [$node->id]);
                        }
                    )
                );

                $typeContainer->addType(
                    self::TYPE_HOME_PAGE,
                    new Type(
                        'Главная страница',
                        true,
                        self::REPO_HOME_PAGE,
                        function () {
                            return route('home');
                        }
                    )
                );
                $typeContainer->addType(
                    self::TYPE_TEXT_PAGE,
                    new Type(
                        'Текстовая страница',
                        false,
                        self::REPO_TEXT_PAGE,
                        function (Node $node) use ($typeContainer) {
                            return route('dynamic_page', $typeContainer->getDynamicPageRelativePath($node->id));
                        }
                    )
                );

                $typeContainer->addType(
                    self::TYPE_PRODUCT_TYPE_PAGE,
                    new Type(
                        'Тип товаров',
                        false,
                        self::REPO_PRODUCT_TYPE_PAGE,
                        function (Node $node) use ($typeContainer) {
                            return route('dynamic_page', $typeContainer->getDynamicPageRelativePath($node->id));
                        }
                    )
                );

                $typeContainer->addType(
                    self::TYPE_REVIEWS_PAGE,
                    new Type(
                        'Отзывы',
                        true,
                        self::REPO_REVIEWS_PAGE,
                        function () {
                            return route('reviews');
                        }
                    )
                );

                $typeContainer->addType(
                    self::TYPE_MAP_PAGE,
                    new Type(
                        'Карта сайта',
                        true,
                        self::REPO_MAP_PAGE,
                        function () {
                            return route('site_map');
                        }
                    )
                );

                $typeContainer->addType(
                    self::TYPE_MEASUREMENT_PAGE,
                    new Type(
                        'Вызов замерщика',
                        true,
                        self::REPO_MEASUREMENT_PAGE,
                        function () {
                            return route('measurement');
                        }
                    )
                );

                $typeContainer->addType(
                    self::TYPE_ERROR_404,
                    new Type(
                        'Страница 404',
                        true,
                        self::REPO_ERROR_404,
                        function () {
                            return route('404');
                        }
                    )
                );

                return $typeContainer;
            }
        );
    }
}
