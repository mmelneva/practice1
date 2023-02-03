@extends('admin.resource_list.form')

@section('title')
    {{{ array_get($resource_texts, 'create_title', 'Создание') }}}
@stop

@section('submit_block')
    <button type="submit" class="btn btn-success">{{{ trans('interactions.create') }}}</button>
    <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.create_and_back_to_list') }}}</button>
    <a href="{{{ action($resource_controller . '@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
@stop