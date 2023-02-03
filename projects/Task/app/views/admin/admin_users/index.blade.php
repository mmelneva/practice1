@extends('admin.layouts.default')

@section('title')
    Администраторы
@stop

@section('content')
    <div class="element-list-wrapper user-list">
        @include('admin.admin_users._create_button')

        <div class="element-container header-container">
            <div class="name">{{{ trans('validation.attributes.username') }}}</div>
            <div class="role">{{{ trans('validation.attributes.admin_role_id') }}}</div>
            <div class="ip">{{{ trans('validation.attributes.allowed_ips') }}}</div>
            <div class="control">{{{ trans('interactions.controls') }}}</div>
        </div>

        <ul class="element-list">
            @foreach ($user_list as $user)
                <li>
                    <div class="element-container">
                        <div class="name">
                            <a href="{{{ action('App\Controllers\Admin\AdminUsersController@getEdit', [$user->id]) }}}">
                                {{{ $user->username }}}
                            </a>
                        </div>
                        <div class="role">
                            @if ($user->super)
                                <span class="super-user">Суперпользователь</span>
                            @elseif (!is_null($user->role))
                                {{{ $user->role->name }}}
                            @endif
                        </div>
                        <div class="ip">
                            @if (count($user->allowed_ips) == 0 || count($user->allowed_ips) == 1 && $user->allowed_ips[0] == '')
                                Все IP
                            @else
                                {{ implode('<br />', $user->allowed_ips) }}
                            @endif
                        </div>
                        <div class="control">
                            @include('admin.shared._element_list_controls', ['element' => $user, 'controller' => 'App\Controllers\Admin\AdminUsersController', 'disable_delete' => Auth::user()->id == $user->id, 'delete_confirm' => trans('resources.admin_users.delete_confirm')])
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        @include('admin.admin_users._create_button')
    </div>
@stop