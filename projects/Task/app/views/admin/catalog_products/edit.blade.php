@extends('admin.catalog_categories.inner')

@section('title', 'Редактирование товара "' . $product->name . '"')

@section('content')
    {{ Form::tbModelWithErrors($product, $errors, ['url' => action('App\Controllers\Admin\CatalogProductsController@putUpdate', [$product->id]), 'method' => 'put', 'files' => true]) }}

        @include('admin.catalog_products._product_form_fields')

        <div class="form-group">
            <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
            <a href="{{{ action('App\Controllers\Admin\CatalogProductsController@getIndex', [$category->id]) }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
            <a class="btn btn-danger"
               data-method="delete"
               data-confirm="Вы уверены, что хотите удалить данный товар?"
               href="{{{ action('App\Controllers\Admin\CatalogProductsController@deleteDestroy', $product->id) }}}">{{{ trans('interactions.delete') }}}</a>
        </div>

    {{ Form::close() }}
@stop