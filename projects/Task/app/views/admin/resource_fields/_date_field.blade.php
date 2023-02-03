{{ Form::tbFormGroupOpen($field['field']) }}
    {{ Form::tbLabel($field['field'], trans('validation.attributes.' . $field['field'])) }}
    {{ Form::tbText(
        $field['field'],
        !empty($resource->{$field['field']}) ? date(!empty($field['format']) ? $field['format'] : "d.m.Y H:i:s", strtotime($resource->{$field['field']})) : '',
        !empty($field['calendar']) ? $field['calendar'] : ['data-datetimepicker' => '']
    ) }}
{{ Form::tbFormGroupClose() }}