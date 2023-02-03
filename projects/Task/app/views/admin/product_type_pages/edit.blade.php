@extends('admin.structure.inner')
{{-- Edit product type pages --}}

@section('title')
    {{{ $node->name }}} - редактирование содержимого
@stop

@section('content')
    {{ Form::tbModelWithErrors($productTypePage, $errors, ['url' => action('App\Controllers\Admin\ProductTypePagesController@putUpdate', [$node->id]), 'method' => 'put', 'files' => true, 'id' => 'product-type-page', 'autocomplete' => 'off']) }}
        {{ Form::tbTextBlock('header') }}

        {{ Form::tbSelectBlock('parent_category_id', $rootCategoryVariants, null, $parentCategorySelectedVariant) }}

        {{ Form::tbCheckboxBlock('in_popular') }}
        <div id="popular-container">
            {{ Form::tbTextBlock('popular_name') }}
        </div>

        {{ Form::tbCheckboxBlock('use_reviews_associations') }}

        {{ Form::tbTinymceTextareaBlock('content') }}
        {{ Form::tbTinymceTextareaBlock('content_bottom') }}

        {{ Form::tbTinymceTextareaBlock('short_content', null, null, ['rows' => 5]) }}


        {{ Form::tbCheckboxBlock('content_header_show') }}
        {{ Form::tbTinymceTextareaBlock('content_header', null, null, ['rows' => 5]) }}

        {{ Form::tbCheckboxBlock('order_icon_type') }}

        @include('admin.product_type_pages._products')

        {{ Form::tbCheckboxBlock('use_sort_scheme') }}

        @include('admin.shared._form_meta_fields')
        @include('admin.shared._model_timestamps', ['model' => $productTypePage])

        <div class="form-group">
            <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
            @include('admin.structure._delete_node', ['node' => $node])
            <a href="{{{ action('App\Controllers\Admin\StructureController@getEdit', [$node->id]) }}}" class="btn btn-default">{{{ trans('interactions.edit') }}}</a>
            <a href="{{{ action('App\Controllers\Admin\StructureController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        </div>

    {{ Form::close() }}
@stop