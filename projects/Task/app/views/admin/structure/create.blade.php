@extends('admin.structure.inner')

@section('title', 'Создание страницы')

@section('content')
    {{ Form::tbModelWithErrors($node, $errors, ['url' => action('App\Controllers\Admin\StructureController@postStore'), 'method' => 'post']) }}

        @include('admin.structure._node_form_fields')

        {{ Form::tbFormGroupOpen() }}
            <button type="submit" class="btn btn-success">{{{ trans('interactions.create') }}}</button>
            <button type="submit" class="btn btn-primary" name="redirect_to" value="index">{{{ trans('interactions.create_and_back_to_list') }}}</button>
            <a href="{{{ action('App\Controllers\Admin\StructureController@getIndex') }}}" class="btn btn-default">{{{ trans('interactions.back_to_list') }}}</a>
        {{ Form::tbFormGroupClose() }}

    {{ Form::close() }}
@stop