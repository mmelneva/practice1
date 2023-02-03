<?php namespace App\Services\Providers;

use App\Services\FormProcessors\AdminRoleFormProcessor;
use App\Services\FormProcessors\AdminUserFormProcessor;
use App\Services\FormProcessors\AttributeFormProcessor;
use App\Services\FormProcessors\CatalogCategoryFormProcessor;
use App\Services\FormProcessors\CatalogProductFormProcessor;
use App\Services\FormProcessors\HomePageFormProcessor;
use App\Services\FormProcessors\NodeFormProcessor;
use App\Services\FormProcessors\ReviewsFormProcessor\AdminReviewsFormProcessor;
use App\Services\Validation\AdminRole\AdminRoleLaravelValidator;
use App\Services\Validation\AdminUser\AdminUserLaravelValidator;
use App\Services\Validation\Node\NodeLaravelValidator;
use Illuminate\Support\ServiceProvider;

class FormProcessorsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\FormProcessors\AdminUserFormProcessor',
            function () {
                return new AdminUserFormProcessor(
                    new AdminUserLaravelValidator($this->app->make('validator')),
                    $this->app->make('App\Services\Repositories\AdminUser\AdminUserRepositoryInterface')
                );
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\AdminRoleFormProcessor',
            function () {
                return new AdminRoleFormProcessor(
                    new AdminRoleLaravelValidator($this->app->make('validator'), $this->app->make('admin_acl')),
                    $this->app->make('App\Services\Repositories\AdminRole\AdminRoleRepositoryInterface')
                );
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\NodeFormProcessor',
            function () {
                return new NodeFormProcessor(
                    new NodeLaravelValidator($this->app->make('validator'), $this->app->make('structure_types.types')),
                    $this->app->make('App\Services\Repositories\Node\NodeRepositoryInterface'),
                    $this->app->make('structure_types.types')
                );
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\CatalogCategoryFormProcessor',
            function () {
                $categoryFormProcessor = new CatalogCategoryFormProcessor(
                    $this->app->make('App\Services\Validation\CatalogCategory\CatalogCategoryLaravelValidator'),
                    $this->app->make('App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface')
                );

                return $categoryFormProcessor;
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\CatalogProductFormProcessor',
            function () {
                $productFormProcessor = new CatalogProductFormProcessor(
                    $this->app->make('App\Services\Validation\CatalogProduct\CatalogProductLaravelValidator'),
                    $this->app->make('App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface')
                );

                $productFormProcessor->addSubProcessor(
                    $this->app->make(
                        'App\Services\FormProcessors\CatalogProductsSubProcessor\AdditionalAttributesSubProcessor'
                    )
                );

                $productFormProcessor->addSubProcessor(
                    $this->app->make(
                        'App\Services\FormProcessors\CatalogProductsSubProcessor\GalleryImagesSubProcessor'
                    )
                );

                $productFormProcessor->addSubProcessor(
                    $this->app->make(
                        'App\Services\FormProcessors\CatalogProductsSubProcessor\AssociatedCategoriesSubProcessor'
                    )
                );

                return $productFormProcessor;
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\AttributeFormProcessor',
            function () {
                $productFormProcessor = new AttributeFormProcessor(
                    $this->app->make('App\Services\Validation\Attribute\AttributeLaravelValidator'),
                    $this->app->make('App\Services\Repositories\Attribute\AttributeRepositoryInterface')
                );

                $productFormProcessor->addSubProcessor(
                    $this->app->make(
                        'App\Services\FormProcessors\AttributesSubProcessor\AllowedValuesSubProcessor'
                    )
                );

                return $productFormProcessor;
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\HomePageFormProcessor',
            function () {
                $homePageFormProcessor = new HomePageFormProcessor(
                    $this->app->make('App\Services\Validation\HomePage\HomePageLaravelValidator')
                );

                $homePageFormProcessor->addSubProcessor(
                    $this->app->make(
                        'App\Services\FormProcessors\HomePagesSubProcessor\BannersSubProcessor'
                    )
                );

                $homePageFormProcessor->addSubProcessor(
                    $this->app->make(
                        'App\Services\FormProcessors\HomePagesSubProcessor\ProductsSubProcessor'
                    )
                );

                return $homePageFormProcessor;
            }
        );

        $this->app->bind(
            'App\Services\FormProcessors\ReviewsFormProcessor\AdminReviewsFormProcessor',
            function () {

                $reviewFormProcessor = new AdminReviewsFormProcessor(
                        $this->app->make('App\Services\Validation\Reviews\AdminReviewsLaravelValidator'),
                        $this->app->make('App\Services\Repositories\Reviews\ReviewsRepositoryInterface')
                );

                $reviewFormProcessor->addSubProcessor(
                        $this->app->make(
                                'App\Services\FormProcessors\ReviewsSubProcessor\AssociatedCategoriesSubProcessor'
                        )
                );
                $reviewFormProcessor->addSubProcessor(
                        $this->app->make(
                                'App\Services\FormProcessors\ReviewsSubProcessor\AssociatedProductTypePagesSubProcessor'
                        )
                );
                return $reviewFormProcessor;
            }
        );


    }
}
