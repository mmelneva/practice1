@extends('admin.catalog_categories.inner')

@section('title', 'Редактирование категории "' . $category->name . '"')

@section('content')
    {{ Form::tbModelWithErrors($category, $errors, ['url' => action('App\Controllers\Admin\CatalogCategoriesController@putUpdate', [$category->id]), 'method' => 'put', 'files' => true]) }}

        @include('admin.catalog_categories._category_form_fields')

        <div class="form-group">
            <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
            <a href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
            <a class="btn btn-danger"
               data-method="delete"
               data-confirm="Вы уверены, что хотите удалить данную категорию?"
               href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@deleteDestroy', $category->id) }}}">{{{ trans('interactions.delete') }}}</a>
        </div>

    {{ Form::close() }}
@stop