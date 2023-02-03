@extends('admin.layouts.guest')
{{-- 500 --}}

@section('title')
    При обработке запроса возникла ошибка!
@stop

@section('content')
    <p>
        Приносим извинения за причиненные неудобства.
        Мы зафиксировали параметры ошибки и постараемся ее исправить в ближайшее время.
    </p>
@stop

@section('container_attributes')
    style="width: 530px;"
@stop