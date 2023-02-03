@extends('admin.layouts.default')
{{-- Layout for user forms --}}


@section('main_menu_class', 'closed')

@section('second_column')
    {{ HTML::additionalMenuOpen(['resize' => 'admin-users']) }}
        <div class="menu-wrapper">
            <div class="menu-header"><a href="{{{ action('App\Controllers\Admin\AdminUsersController@getIndex') }}}">Администраторы</a></div>
            <div class="menu-sub-header">
                <a href="{{{ action('App\Controllers\Admin\AdminUsersController@getCreate') }}}" class="btn btn-success btn-xs">Добавить администратора</a>
            </div>
            <ul>
                @foreach ($user_list as $u)
                    <li>
                        <div class="menu-element {{{ $user->id == $u->id ? 'active' : '' }}}">
                            <div class="name">
                                <a href="{{{ action('App\Controllers\Admin\AdminUsersController@getEdit', [$u->id]) }}}"
                                   title="{{{ $u->username }}}">{{{ $u->username }}}</a>
                            </div>
                            <div class="control">
                                @include('admin.shared._element_list_controls', ['element' => $u, 'controller' => 'App\Controllers\Admin\AdminUsersController', 'disable_delete' => Auth::user()->id == $u->id, 'delete_confirm' => trans('resources.admin_users.delete_confirm')])
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="menu-footer">
                <a href="{{{ action('App\Controllers\Admin\AdminUsersController@getCreate') }}}" class="btn btn-success btn-xs">Добавить администратора</a>
            </div>
        </div>
    {{ HTML::additionalMenuClose() }}
@stop

@section('content')

    {{ Form::tbRestfulFormOpen($user, $errors, 'App\Controllers\Admin\AdminUsersController' ,['id' => 'admin_user_form']) }}

        {{ Form::tbFormGroupOpen('username') }}
            {{ Form::tbLabel('username', trans('validation.attributes.username')) }}
            {{ Form::tbText('username') }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen('password') }}
            {{ Form::tbLabel('password', trans('validation.attributes.password')) }}
            {{ Form::tbPassword('password') }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen('password_confirmation') }}
            {{ Form::tbLabel('password', trans('validation.attributes.password_confirmation')) }}
            {{ Form::tbPassword('password_confirmation') }}
        {{ Form::tbFormGroupClose() }}

        @if (Auth::user()->id != $user->id)
            {{ Form::tbFormGroupOpen('active') }}
                <input type="hidden" name="active" value="0" />
                <label class="checkbox-inline">
                    {{ Form::checkbox('active', 1) }}
                    <span class="bold">{{{ trans('validation.attributes.active') }}}</span>
                </label>
            {{ Form::tbFormGroupClose() }}


            {{ Form::tbFormGroupOpen('admin_role_id') }}
                {{ Form::tbLabel('admin_role_id', trans('validation.attributes.admin_role_id')) }}
                {{ Form::tbSelect('admin_role_id', $available_roles) }}
            {{ Form::tbFormGroupClose() }}
        @endif

        @include('admin.admin_users._ip_list_container', ['ips' => Input::old('allowed_ips') ? Input::old('allowed_ips') : array_get($user, 'allowed_ips', [])])
        @include('admin.shared._model_timestamps', ['model' => $user])

        {{ Form::tbFormGroupOpen() }}
            @yield('submit_block')
        {{ Form::tbFormGroupClose() }}

    {{ Form::close() }}

@stop