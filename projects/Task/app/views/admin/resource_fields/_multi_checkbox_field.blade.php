{{ Form::tbFormGroupOpen($field['field']) }}
    {{ Form::tbLabel($field['field'], trans('validation.attributes.' . $field['field'])) }}
    <div class="multi-checkbox">
        @foreach (array_chunk(${$field['list']}, 4, true) as $chunk)
            <div class="multi-checkbox-row">
                @foreach ($chunk as $elementId => $elementName)
                    <div class="multi-checkbox-element">
                        <label class="checkbox-inline">
                            {{ Form::checkbox("{$field['field']}[]", $elementId, in_array($elementId, ${$field['checked']})) }}
                            {{{ $elementName }}}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
{{ Form::tbFormGroupClose() }}