{{ Form::tbFormGroupOpen('rules') }}
    {{ Form::tbLabel('rules', trans('validation.attributes.rules')) }}
    <div class="available-resources">
        @foreach (array_chunk($acl_rules, 3, true) as $rules_chunk)
            <div class="resource-chunk">
                @foreach ($rules_chunk as $acl_rule_key => $acl_rule_name)
                    <div class="resource">
                        <label class="checkbox-inline">
                            <input name="rules[]" value="{{{ $acl_rule_key }}}" type="checkbox" {{ is_null($resource->id) || in_array($acl_rule_key, Input::old('rules', $resource->rules)) ? 'checked="checked"' : '' }} />
                            {{{ $acl_rule_name }}}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
{{ Form::tbFormGroupClose() }}