@extends('admin.catalog_categories.inner')

@section('title', 'Добавление товара')

@section('content')
    {{ Form::tbModelWithErrors($product, $errors, ['url' => action('App\Controllers\Admin\CatalogProductsController@postStore', [$category->id]), 'method' => 'post', 'files' => true]) }}

        @include('admin.catalog_products._product_form_fields')

        <div class="form-group">
            <button type="submit" class="btn btn-success">{{{ trans('interactions.create') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.create_and_back_to_list') }}}</button>
            <a href="{{{ action('App\Controllers\Admin\CatalogProductsController@getIndex', [$category->id]) }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        </div>

    {{ Form::close() }}
@stop