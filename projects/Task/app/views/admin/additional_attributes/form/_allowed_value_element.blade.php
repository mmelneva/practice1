<li data-element-list="element" data-element-key="{{ $key }}">
    <div>
        <span data-element-list="remove" class="remove glyphicon glyphicon-remove"></span>
        @if ($allowed_value->exists)
            <span data-element-list="toggle-expand" class="toggle-expand glyphicon glyphicon-collapse-down"></span>
        @endif
    </div>

    <div data-element-list="header" class="header {{{ !$allowed_value->exists ? 'dnone' : '' }}}">
        <span data-element-list="edit">{{{ array_get($allowed_value, 'value', '-') }}}</span>
    </div>
    <div data-element-list="fields" class="{{{ !$allowed_value->exists ? '' : 'dnone' }}}">
        {{ Form::hidden("allowed_values[{$key}][id]", array_get($allowed_value, 'id')) }}

        {{ Form::tbFormGroupOpen("allowed_values.{$key}.value") }}
            {{ Form::tbLabel("allowed_values[{$key}][value]", trans('validation.attributes.value')) }}
            {{ Form::tbText("allowed_values[{$key}][value]", array_get($allowed_value, 'value')) }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen("allowed_values.{$key}.position") }}
            {{ Form::tbLabel("allowed_values[{$key}][position]", trans('validation.attributes.position')) }}
            {{ Form::tbText("allowed_values[{$key}][position]", array_get($allowed_value, 'position')) }}
        {{ Form::tbFormGroupClose() }}

    </div>
</li>