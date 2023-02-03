@extends('admin.layouts.default')

@section('main_menu_class', 'closed')

@section('second_column')
    {{ HTML::additionalMenuOpen(['resize' => 'resource-' . md5(array_get($resource_texts, 'list_title', 'Список'))]) }}
        <div class="menu-wrapper">
            <div class="menu-header"><a href="{{{ action($resource_controller . '@getIndex') }}}">{{{ array_get($resource_texts, 'list_title', 'Список') }}}</a></div>
            @if(empty($no_create))
                <div class="menu-sub-header">
                    <a href="{{{ wrap_with_paginator(action($resource_controller . '@getCreate'), $resource_list) }}}" class="btn btn-success btn-xs">{{{ array_get($resource_texts, 'add_new', 'Добавить') }}}</a>
                </div>
            @endif
            <ul>
                @foreach ($resource_list as $r)
                    <li>
                        <div class="menu-element {{{ $resource->id == $r->id ? 'active' : '' }}}">
                            @foreach ($menu_columns as $column)
                                @include($column['template'], ['column' => $column, 'resource' => $r])
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ul>

            @include('admin.shared._pagination_simple_links', ['paginator' => $resource_list])

            @if(empty($no_create))
                <div class="menu-footer">
                    <a href="{{{ wrap_with_paginator(action($resource_controller . '@getCreate'), $resource_list) }}}" class="btn btn-success btn-xs">{{{ array_get($resource_texts, 'add_new', 'Добавить') }}}</a>
                </div>
            @endif
        </div>
    {{ HTML::additionalMenuClose() }}
@stop


@section('content')
    {{ Form::tbRestfulFormOpen($resource, $errors, $resource_controller, ['autocomplete' => 'off', 'id' => isset($form_id) ? $form_id : 'resource-form']) }}
        @foreach ($form_fields as $field)
            @include($field['template'], ['field' => $field])
        @endforeach

        {{ Form::tbFormGroupOpen() }}
            @yield('submit_block')
        {{ Form::tbFormGroupClose() }}

        {{ Form::hidden('page', \Input::get('page')) }}
    {{ Form::close() }}

@stop