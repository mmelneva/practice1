{{ Form::tbFormGroupOpen($field['field']) }}
    {{ Form::tbLabel($field['field'], trans('validation.attributes.' . $field['field'])) }}
    {{ Form::tbSelect($field['field'], $status_list) }}
{{ Form::tbFormGroupClose() }}