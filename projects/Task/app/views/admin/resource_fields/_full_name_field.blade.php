{{ Form::tbFormGroupOpen('name') }}
    {{ Form::tbLabel('name', trans('validation.attributes.full_name')) }}
    {{ Form::tbText('name') }}
{{ Form::tbFormGroupClose() }}