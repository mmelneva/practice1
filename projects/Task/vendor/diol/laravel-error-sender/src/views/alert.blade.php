@extends('laravel-error-sender::layout')

@section('content')

    <p><span style="color: red;">Внимание!</span> На сайте <a href="{{{ Request::root() }}}">{{{ Request::root() }}}</a> произошла критическая ошибка.</p>
    <div style="font-size: 14px;">
        <div><strong>Описание возникшей ошибки</strong>:</div>

        <div style="margin: 10px; font-size: 13px">
            @foreach([
                'Код состояния HTTP' => $code,
                'Тип интерфейса между веб-сервером и PHP' => $sapi,
                'Класс исключения' => get_class($exception),
                'Код исключения' => $exception->getCode(),
                'Сообщение исключения' => $exception->getMessage(),
                'Файл, в котором произошло исключение' => $exception->getFile(),
                'Строка, в которой произошло исключение' => $exception->getLine(),
            ] as $key => $value)
                @if ($value !== '')
                    <div><strong>{{{ $key }}}</strong>: {{{ $value }}}</div>
                @endif
            @endforeach
        </div><br>

        <div><strong>[Stack trace]</strong>:
            <br>
            <div style="font-size: 11px;">
                @foreach(explode(PHP_EOL, $exception->getTraceAsString()) as $traceLine)
                    {{{ $traceLine }}}<br>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div style="color: gray; font-size: 12px; margin: 20px 0; padding-top: 10px; border-top: 1px dashed;">
        <div style="margin: 0 15px;">
            <i>Подробное описание ошибки см. во вложении.</i>
        </div>
    </div>
@stop

