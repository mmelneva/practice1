{{ Form::tbFormGroupOpen($field['field']) }}
    <strong>{{{ trans('validation.attributes.order_number') }}}</strong>: {{{ $resource->{$field['field']} }}}
{{ Form::tbFormGroupClose() }}