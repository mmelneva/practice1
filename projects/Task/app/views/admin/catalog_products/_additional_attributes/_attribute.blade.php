<li class="attribute">
    @if ($attribute->is_list)

    {{  Form::tbFormGroupOpen("additional_attributes.{$attribute->id}.allowed_value_id") }}
        {{ Form::tbLabel("additional_attributes[{$attribute->id}][allowed_value_id]", $attribute->name) }}
        {{ Form::tbSelect("additional_attributes[{$attribute->id}][allowed_value_id]", $variants, isset($value) ? $value : 0, ['id' => "additional{$attribute->id}"]) }}
    {{ Form::tbFormGroupClose()}}

    @elseif($attribute->is_multiple_values)
        {{  Form::tbFormGroupOpen("additional_attributes.{$attribute->id}.allowed_value_id_list") }}
            {{ Form::label('additional' . $attribute->id, $attribute->name) }}
            {{ Form::hidden("additional_attributes[$attribute->id][multiple_values]", '1') }}
            <div class="multiple-value-container">
                @foreach ($variants as $id => $v)
                    <label class="checkbox-inline">
                        <input type="checkbox"
                               name="additional_attributes[{{{ $attribute->id }}}][allowed_value_id_list][]"
                                {{ in_array($id, \Input::old("additional_attributes.{$attribute->id}.allowed_value_id_list", isset($value) ? $value : [])) ? 'checked="checked"' : '' }}
                               value="{{ $id }}"> <span>{{ $v }}</span>
                    </label>
                @endforeach
            </div>
        {{ Form::tbFormGroupClose(); }}
    @else
        {{ Form::tbFormGroupOpen("additional_attributes.{$attribute->id}.value") }}
            @if($attribute->is_number)
            {{ Form::hidden("additional_attributes[$attribute->id][is_number_value]", '1') }}
            @endif
            {{ Form::tbLabel("additional_attributes[{$attribute->id}][value]", $attribute->name) }}
            {{ Form::tbText("additional_attributes[{$attribute->id}][value]", isset($value) ? $value : null, ['id' => "additional.{$attribute->id}"]) }}
        {{ Form::tbFormGroupClose() }}
    @endif
</li>