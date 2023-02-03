<?php namespace App\Services\Providers;

use App\Services\Repositories\CatalogCategory\EloquentCatalogCategoryRepository;
use App\Services\Repositories\Node\EloquentNodeRepository;
use App\Services\Repositories\ProductTypePage\EloquentProductTypeRepository;
use App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler;
use App\Services\RepositoryFeatures\Attribute\PositionUpdater;
use App\Services\RepositoryFeatures\Caching\CachingExecutor;
use App\Services\RepositoryFeatures\Order\PositionOrderScopes;
use App\Services\RepositoryFeatures\Tree\PublishedTreeBuilder;
use App\Services\RepositoryFeatures\Variants\PossibleVariants;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\Repositories\AdminUser\AdminUserRepositoryInterface',
            'App\Services\Repositories\AdminUser\EloquentAdminUserRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\AdminRole\AdminRoleRepositoryInterface',
            'App\Services\Repositories\AdminRole\EloquentAdminRoleRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\Node\NodeRepositoryInterface',
            function () {
                return new EloquentNodeRepository(
                    new PositionOrderScopes(),
                    new PublishedTreeBuilder(new PositionOrderScopes(), new CachingExecutor()),
                    new EloquentAttributeToggler(),
                    new PositionUpdater(),
                    new PossibleVariants()
                );
            }
        );

        $this->app->bind(
            'App\Services\Repositories\HomePage\HomePageRepositoryInterface',
            'App\Services\Repositories\HomePage\EloquentHomePageRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\TextPage\TextPageRepositoryInterface',
            'App\Services\Repositories\TextPage\EloquentTextPageRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface',
            function () {
                return new EloquentCatalogCategoryRepository(
                    new PublishedTreeBuilder(new PositionOrderScopes(), new CachingExecutor()),
                    new EloquentAttributeToggler(),
                    new PositionUpdater()
                );
            }
        );

        $this->app->bind(
            'App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface',
            'App\Services\Repositories\CatalogProduct\EloquentCatalogProductRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\SettingGroup\SettingGroupRepositoryInterface',
            'App\Services\Repositories\SettingGroup\EloquentSettingGroupRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\Setting\SettingRepositoryInterface',
            'App\Services\Repositories\Setting\EloquentSettingRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\MetaPage\MetaPageRepositoryInterface',
            'App\Services\Repositories\MetaPage\EloquentMetaPageRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\Attribute\AttributeRepositoryInterface',
            'App\Services\Repositories\Attribute\EloquentAttributeRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\AttributeAllowedValue\AttributeAllowedValueRepositoryInterface',
            'App\Services\Repositories\AttributeAllowedValue\EloquentAttributeAllowedValueRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\AttributeValue\AttributeValueRepositoryInterface',
            'App\Services\Repositories\AttributeValue\EloquentAttributeValueRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\Order\OrderRepositoryInterface',
            'App\Services\Repositories\Order\EloquentOrderRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\ProductTypePage\ProductTypePageRepositoryInterface',
            function () {
                return new EloquentProductTypeRepository(
                    new PublishedTreeBuilder(new PositionOrderScopes(), new CachingExecutor()),
                    $this->app->make('App\Services\Repositories\Node\NodeRepositoryInterface'),
                    $this->app->make('App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface'),
                    $this->app->make('App\Services\RepositoryFeatures\Attribute\EloquentAttributeToggler')
                );
            }
        );
        $this->app->bind(
            'App\Services\Repositories\ProductTypePageAssociation\ProdTypePageAssociationRepoInterface',
            'App\Services\Repositories\ProductTypePageAssociation\EloquentProdTypePageAssociationRepo'
        );

        $this->app->bind(
            'App\Services\Repositories\ProductHomePageAssociation\ProductHomePageAssociationRepoInterface',
            'App\Services\Repositories\ProductHomePageAssociation\EloquentHomePageAssociationRepo'
        );

        $this->app->bind(
            'App\Services\Repositories\Callback\CallbackRepositoryInterface',
            'App\Services\Repositories\Callback\EloquentCallbackRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\Banner\BannerRepositoryInterface',
            'App\Services\Repositories\Banner\EloquentBannerRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\ProductGalleryImage\ProductGalleryImageRepositoryInterface',
            'App\Services\Repositories\ProductGalleryImage\EloquentProductGalleryImageRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\Reviews\ReviewsRepositoryInterface',
            'App\Services\Repositories\Reviews\EloquentReviewsRepository'
        );

        $this->app->bind(
            'App\Services\Repositories\ReviewsDateChange\ReviewsDateChangeRepositoryInterface',
            'App\Services\Repositories\ReviewsDateChange\EloquentReviewsDateChangeRepository'
        );
    }
}
