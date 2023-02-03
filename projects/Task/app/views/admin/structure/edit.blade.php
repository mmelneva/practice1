@extends('admin.structure.inner')

@section('title')
{{{ $node->name }}} - редактирование
@stop

@section('content')
    {{ Form::tbModelWithErrors($node, $errors, ['url' => action('App\Controllers\Admin\StructureController@putUpdate', [$node->id]), 'method' => 'put']) }}

        @include('admin.structure._node_form_fields')
        {{ Form::hidden('position', $node->position) }}

        {{ Form::tbFormGroupOpen() }}
            <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
            @include('admin.structure._delete_node', ['node' => $node])
            <a href="{{{ TypeContainer::getContentUrl($node) }}}" class="btn btn-default">{{{ trans('interactions.edit') }}}</a>
            <a href="{{{ action('App\Controllers\Admin\StructureController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        {{ Form::tbFormGroupClose() }}

    {{ Form::close() }}
@stop