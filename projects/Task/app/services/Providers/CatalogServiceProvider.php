<?php namespace App\Services\Providers;

use App\Services\Catalog\Filter\Filter\FilterLensAggregator;
use App\Services\Catalog\Filter\Filter\FilterLensWrapper;
use App\Services\Catalog\Filter\Lens\BuiltInLens;
use App\Services\Catalog\Filter\Lens\ClassicListLens;
use App\Services\Catalog\Filter\Lens\ClassicMultipleLens;
use App\Services\Catalog\Filter\Lens\ClassicStringLens;
use App\Services\Catalog\Filter\Lens\RangeLens;
use App\Services\Catalog\PathFinder\CatalogPathFinder;
use App\Services\Catalog\UrlBuilder\CatalogUrlBuilder;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'catalog.catalog_url_builder',
            function () {
                return new CatalogUrlBuilder('catalog');
            }
        );

        $this->app->singleton(
            'catalog.catalog_path_finder',
            function () {
                return new CatalogPathFinder();
            }
        );

        // Filter
        $this->app->singleton(
            'catalog.filter',
            function () {
                $filter = new FilterLensAggregator();
                $attrRepo = $this->app->make('App\Services\Repositories\Attribute\AttributeRepositoryInterface');
                $allowedValueRepo = $this->app->make(
                    'App\Services\Repositories\AttributeAllowedValue\AttributeAllowedValueRepositoryInterface'
                );

                $attributes = $attrRepo->all();
                foreach ($attributes as $attribute) {
                    $attrName = trim($attribute->name);
                    if ($attribute->is_string) {
                        $filter->addLens(
                            new FilterLensWrapper(
                                new ClassicStringLens($attrRepo, $attribute),
                                'attr' . $attribute->id,
                                $attrName,
                                null,
                                'multiple_checkboxes'
                            )
                        );
                    } elseif ($attribute->is_number) {
                        $filter->addLens(
                            new FilterLensWrapper(
                                new RangeLens($attrRepo, $attribute),
                                'attr' . $attribute->id,
                                $attrName,
                                null,
                                'range'
                            )
                        );
                    } elseif ($attribute->is_list) {
                        $filter->addLens(
                            new FilterLensWrapper(
                                new ClassicListLens($attrRepo, $allowedValueRepo, $attribute),
                                'attr' . $attribute->id,
                                $attrName,
                                null,
                                'multiple_checkboxes'
                            )
                        );
                    } elseif ($attribute->is_multiple_values) {
                        $filter->addLens(
                            new FilterLensWrapper(
                                new ClassicMultipleLens($attrRepo, $allowedValueRepo, $attribute),
                                'attr' . $attribute->id,
                                $attrName,
                                null,
                                'multiple_checkboxes'
                            )
                        );
                    }
                }

                $filter->addLens(
                    new FilterLensWrapper(
                        new BuiltInLens(),
                        'built_in',
                        'Шкаф встроенный',
                        null,
                        'radios'
                    )
                );

                return $filter;
            }
        );

        $this->app->bind('App\Services\Catalog\Filter\Filter\FilterInterface', 'catalog.filter');
    }
}
