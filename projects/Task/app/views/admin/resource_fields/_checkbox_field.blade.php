{{ Form::tbFormGroupOpen($field['field']) }}
    <input type="hidden" value="0" name="{{{ $field['field'] }}}">
    <label class="checkbox-inline">{{ Form::checkbox($field['field']) }} <span class="bold">{{{ trans('validation.attributes.' . $field['field']) }}}</span></label>
{{ Form::tbFormGroupClose() }}
