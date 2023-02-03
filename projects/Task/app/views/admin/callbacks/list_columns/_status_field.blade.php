<div class="{{{ $column['field'] }}}"
     title="{{{ trans('validation.model_attributes.callback.status.' . $resource->{$column['field']}) }}}">
<span @if ($resource->{$column['field']} == \App\Models\CallbackStatusConstants::NOVEL)
    class="text-success" @endif >
    {{{ trans('validation.model_attributes.callback.status.' . $resource->{$column['field']}) }}}
</span>
</div>
