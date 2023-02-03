{{ Form::tbFormGroupOpen($field['field']) }}
    {{ Form::tbLabel($field['field'], trans('validation.attributes.' . $field['field'])) }}
    {{ Form::tbText($field['field']) }}
{{ Form::tbFormGroupClose() }}