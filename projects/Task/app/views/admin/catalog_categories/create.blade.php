@extends('admin.catalog_categories.inner')

@section('title', 'Создание категории')

@section('content')
    {{ Form::tbModelWithErrors($category, $errors, ['url' => action('App\Controllers\Admin\CatalogCategoriesController@postStore'), 'method' => 'post', 'files' => true]) }}

        @include('admin.catalog_categories._category_form_fields')

        <div class="form-group">
            <button type="submit" class="btn btn-success">{{{ trans('interactions.create') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.create_and_back_to_list') }}}</button>
            <a href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        </div>

    {{ Form::close() }}
@stop