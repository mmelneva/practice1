@extends('admin.catalog_categories.inner')

@section('title', 'Список товаров категории "' . $category->name . '"')

@section('content')
    <div class="product-list element-list-wrapper" data-sortable-wrapper="">
        @include('admin.catalog_products._create_button')
        <div class="element-container header-container">
            @include('admin.resource_list_sortable._list_sorting_header')
            <div class="name">{{{ trans('validation.attributes.name') }}}</div>
            <div class="position">{{{ trans('validation.attributes.position') }}}</div>
            <div class="publish-status">{{{ trans('validation.attributes.publish') }}}</div>
            <div class="control">{{{ trans('interactions.controls') }}}</div>
        </div>

        <div data-sortable-container="">
            @include('admin.catalog_products._product_list')
        </div>

        @include('admin.resource_list_sortable._sorting_controls', ['resource_controller' => 'App\Controllers\Admin\CatalogProductsController'])

        <div class="pagination-container">
            <label class="pagination-on-page-container">
                Элементов на странице:
                <select class="choose-on-page form-control">
                    @foreach ($on_page_variants as $v)
                        <option
                                {{ $productList->getPerPage() == $v ? 'selected="selected"' : '' }}
                                value="{{{ action('App\Controllers\Admin\CatalogProductsController@getIndex', [$category->id]) }}}?on_page={{{ $v }}}&page=1">{{{ $v }}}</option>
                    @endforeach
                </select>
            </label>

            {{ $productList->links() }}
        </div>

        @include('admin.catalog_products._create_button')
    </div>
@stop