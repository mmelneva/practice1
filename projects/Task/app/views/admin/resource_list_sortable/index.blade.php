@extends('admin.layouts.default')

@section('title')
     {{{ array_get($resource_texts, 'list_title', 'Список') }}}
@stop

@section('content')
    <div class="element-list-wrapper" data-sortable-wrapper="" {{ isset($element_list_wrapper_id) ? "id='{$element_list_wrapper_id}'" : ''}}>
        @include('admin.resource_list._create_button')
        <div class="element-container header-container">
            @include('admin.resource_list_sortable._list_sorting_header')
            @foreach ($list_columns as $column)
                @if (isset($column['header_template']))
                    @include($column['header_template'], ['column' => $column])
                @else
                    @include('admin.list_column_headers._standard_header', ['column' => $column])
                @endif
            @endforeach
        </div>

        <div data-sortable-container="">
            @include('admin.resource_list_sortable._list')
        </div>
        @include('admin.resource_list_sortable._sorting_controls')

        @include('admin.resource_list._create_button')
    </div>
@stop