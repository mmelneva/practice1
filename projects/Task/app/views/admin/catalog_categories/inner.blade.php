@extends('admin.layouts.default')

@section('main_menu_class', 'closed')

@section('second_column')
    {{ HTML::additionalMenuOpen(['resize' => 'catalog-categories']) }}
        <div class="menu-wrapper">
            <div class="menu-header"><a href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@getIndex') }}}">Каталог товаров</a></div>
            <div class="menu-sub-header">
                <a href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@getCreate') }}}" class="btn btn-success btn-xs">Добавить категорию</a>
            </div>
            @include('admin.catalog_categories._category_list_menu', array('categoryTree' => $categoryTree, 'lvl' => 0, 'current_category' => isset($category) ? $category : null))
            <div class="menu-footer">
                <a href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@getCreate') }}}" class="btn btn-success btn-xs">Добавить категорию</a>
            </div>
        </div>
    {{ HTML::additionalMenuClose() }}
@stop