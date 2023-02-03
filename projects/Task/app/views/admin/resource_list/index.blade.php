@extends('admin.layouts.default')

@section('title')
    {{{ array_get($resource_texts, 'list_title', 'Список') }}}
@stop

@section('content')
    <div class="element-list-wrapper" {{ isset($element_list_wrapper_id) ? "id='{$element_list_wrapper_id}'" : ''}}>
        @include('admin.resource_list._create_button')
        <div class="element-container header-container">
            @foreach ($list_columns as $column)
                @if (isset($column['header_template']))
                    @include($column['header_template'], ['column' => $column])
                @else
                    @include('admin.list_column_headers._standard_header', ['column' => $column])
                @endif
            @endforeach
        </div>

        <ul class="element-list">
            @foreach ($resource_list as $resource)
                <li>
                    <div class="element-container">
                        @foreach ($list_columns as $column)
                            @include($column['template'], ['column' => $column])
                        @endforeach
                    </div>
                </li>
            @endforeach
        </ul>

        @include('admin.shared._pagination_links', ['paginator' => $resource_list])

        @include('admin.resource_list._create_button')
    </div>
@stop