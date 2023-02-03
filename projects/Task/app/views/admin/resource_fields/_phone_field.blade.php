{{ Form::tbFormGroupOpen($field['field']) }}
    {{ Form::tbLabel($field['field'], trans('validation.attributes.' . $field['field'])) }}
    {{ Form::tbText($field['field'], null, ['data-phone-mask' => '']) }}
{{ Form::tbFormGroupClose() }}