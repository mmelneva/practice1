@extends('admin.structure.inner')
{{-- Edit home pages --}}

@section('title')
    {{{ $node->name }}} - редактирование содержимого
@stop

@section('content')
    {{ Form::tbModelWithErrors($textPage, $errors, ['url' => action('App\Controllers\Admin\TextPagesController@putUpdate', [$node->id]), 'method' => 'put', 'files' => true]) }}
        @include('admin.shared._header_meta_field')

        {{ Form::tbTinymceTextareaBlock('content') }}

        {{ Form::tbTinymceTextareaBlock('short_content', null, null, ['rows' => 5]) }}

        {{ Form::tbCheckboxBlock('contact_form') }}
        @include('admin.shared._form_meta_fields')
    
        @include('admin.shared._model_timestamps', ['model' => $textPage])
    
        {{ Form::tbFormGroupOpen() }}
            <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
            @include('admin.structure._delete_node', ['node' => $node])
            <a href="{{{ action('App\Controllers\Admin\StructureController@getEdit', [$node->id]) }}}" class="btn btn-default">{{{ trans('interactions.edit') }}}</a>
            <a href="{{{ action('App\Controllers\Admin\StructureController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        {{ Form::tbFormGroupClose() }}

    {{ Form::close() }}
@stop