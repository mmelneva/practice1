@extends('admin.layouts.default')

@section('title')
    {{{ 'Структура сайта' }}}
@stop

@section('content')
    <div class="node-list element-list-wrapper" data-sortable-wrapper="">
        @include('admin.structure._create_button')

        <div class="element-container header-container">
            @include('admin.resource_list_sortable._list_sorting_header')
            <div class="name">{{{ trans('validation.attributes.name') }}}</div>
            <div class="position">{{{ trans('validation.attributes.position') }}}</div>
            <div class="publish-status">{{{ trans('validation.attributes.publish') }}}</div>
            <div class="menu_top-status">{{{ trans('validation.attributes.menu_top') }}}</div>
            {{--<div class="scrolled_menu_top-status">{{{ trans('validation.attributes.scrolled_menu_top') }}}</div>--}}
            {{--<div class="menu_bottom-status">{{{ trans('validation.attributes.menu_bottom') }}}</div>--}}
            <div class="use_reviews_associations-status">{{{ trans('validation.attributes.use_reviews_associations_short') }}}</div>
            <div class="alias">{{{ trans('validation.attributes.alias') }}}</div>
            <div class="type">{{{ trans('validation.attributes.type') }}}</div>
            <div class="control">{{{ trans('interactions.controls') }}}</div>
        </div>

        <div data-sortable-container="">
            @include('admin.structure._node_list', ['nodeTree' => $nodeTree, 'lvl' => 0])
        </div>

        @include('admin.resource_list_sortable._sorting_controls', ['resource_controller' => 'App\Controllers\Admin\StructureController'])

        @include('admin.structure._create_button')
    </div>
@stop