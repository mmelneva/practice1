<div>
    {{ Form::tbLabel($field['field'], trans('validation.model_attributes.callback.' . $field['field'])) }}:
    {{ Form::hidden($field['field']) }}
    {{{ trans('validation.model_attributes.callback.callback_type.' . $resource->{$field['field']}) }}}
</div>
