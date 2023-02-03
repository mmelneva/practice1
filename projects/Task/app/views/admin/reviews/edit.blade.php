@extends('admin.resource_list.form')

@section('title')
    {{{ str_replace('{name}', $resource->name, array_get($resource_texts, 'edit_title', 'Редактирование')) }}}
@stop

@section('submit_block')
    <button type="submit" class="btn btn-success">{{{ trans('interactions.save') }}}</button>
    <button type="submit" class="btn btn-info" name="optional" value="send_answer">Сохранить и отправить ответ</button>
    <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.save_and_back_to_list') }}}</button>
    <a href="{{{ action($resource_controller . '@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
@stop