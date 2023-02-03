@extends('admin.layouts.default')

@section('title')
    Каталог товаров
@stop

@section('content')
    <div class="category-list element-list-wrapper" data-sortable-wrapper="">
        @include('admin.catalog_categories._create_button')
        <div class="element-container header-container">
            @include('admin.resource_list_sortable._list_sorting_header')

            <div class="name">{{{ trans('validation.attributes.name') }}}</div>
            <div class="position">{{{ trans('validation.attributes.position') }}}</div>
            <div class="publish-status">{{{ trans('validation.attributes.publish') }}}</div>
            <div class="top_menu-status">{{{ trans('validation.attributes.top_menu') }}}</div>
            <div class="use_reviews_associations-status">{{{ trans('validation.attributes.use_reviews_associations_short') }}}</div>
            <div class="alias">{{{ trans('validation.attributes.alias') }}}</div>
            <div class="control">{{{ trans('interactions.controls') }}}</div>
        </div>

        <div data-sortable-container="">
            @include('admin.catalog_categories._category_list', ['categoryTree' => $categoryTree, 'lvl' => 0])
        </div>

        @include('admin.resource_list_sortable._sorting_controls', ['resource_controller' => 'App\Controllers\Admin\CatalogCategoriesController'])

        @include('admin.catalog_categories._create_button')
    </div>
@stop