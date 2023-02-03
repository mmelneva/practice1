@extends('admin.structure.inner')
{{-- Edit home pages --}}

@section('title')
    {{{ $node->name }}} - редактирование содержимого
@stop

@section('content')
    {{ Form::tbModelWithErrors($homePage, $errors, ['url' => action('App\Controllers\Admin\HomePagesController@putUpdate', [$node->id]), 'method' => 'put', 'files' => true]) }}
        @include('admin.shared._header_meta_field')

        {{ Form::tbFormGroupOpen('content_top') }}
            {{ Form::tbLabel('content_top', trans('validation.attributes.content_top')) }}
            {{ Form::tbTinymceTextarea('content_top') }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen('content') }}
            {{ Form::tbLabel('content', trans('validation.attributes.content_bottom')) }}
            {{ Form::tbTinymceTextarea('content') }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen('content_for_grid') }}
            {{ Form::tbLabel('content_for_grid', trans('validation.attributes.content_for_grid')) }}
            {{ Form::tbTinymceTextarea('content_for_grid') }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbCheckboxBlock('order_icon_type') }}

        @include('admin.home_pages._products')

        @include('admin.shared._form_meta_fields')

        @include('admin.shared._model_timestamps', ['model' => $homePage])

        {{ Form::tbFormGroupOpen() }}
            <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
            @include('admin.structure._delete_node', ['node' => $node])
            <a href="{{{ action('App\Controllers\Admin\StructureController@getEdit', [$node->id]) }}}" class="btn btn-default">{{{ trans('interactions.edit') }}}</a>
            <a href="{{{ action('App\Controllers\Admin\StructureController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        {{ Form::tbFormGroupClose() }}

    {{ Form::close() }}
@stop