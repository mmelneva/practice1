@extends('admin.layouts.guest')
{{-- Login form --}}

@section('title')
    Авторизация
@stop

@section('content')
    {{ Form::open(array('url' => action('App\Controllers\Admin\SessionsController@store'))) }}

        <div class="form-group {{{ (isset($incorrect) && $incorrect) ? 'has-error' : '' }}}">
            {{ Form::label('username', 'Имя пользователя', ['class' => 'control-label']) }}
            {{ Form::text('username', '', ['class' => 'form-control', 'placeholder' => 'Введите логин', 'autofocus' => 'autofocus']) }}
        </div>

        <div class="form-group {{{ (isset($incorrect) && $incorrect) ? 'has-error' : '' }}}">
            {{ Form::label('password', 'Пароль', ['class' => 'control-label']) }}
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Введите пароль']) }}
        </div>

        <div class="remember-me">
            <label>{{ Form::checkbox('remember', 1) }} Запомнить меня</label>
        </div>

        <div class="submit-container">
            <button type="submit" class="btn btn-primary left">Вход</button>
        </div>

    {{ Form::close() }}
@stop