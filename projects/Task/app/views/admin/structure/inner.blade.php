@extends('admin.layouts.default')

@section('main_menu_class', 'closed')

@section('second_column')
    {{ HTML::additionalMenuOpen(['resize' => 'site-structure']) }}
        <div class="menu-wrapper">
            <div class="menu-header"><a href="{{{ action('App\Controllers\Admin\StructureController@getIndex') }}}">Структура сайта</a></div>
            <div class="menu-sub-header">
                <a href="{{{ action('App\Controllers\Admin\StructureController@getCreate') }}}" class="btn btn-success btn-xs">Добавить страницу</a>
            </div>
            @include('admin.structure._node_list_menu', array('nodes' => $nodeTree, 'lvl' => 0, 'current_node' => isset($node) ? $node : null))
            <div class="menu-footer">
                <a href="{{{ action('App\Controllers\Admin\StructureController@getCreate') }}}" class="btn btn-success btn-xs">Добавить страницу</a>
            </div>
        </div>
    {{ HTML::additionalMenuClose() }}
@stop