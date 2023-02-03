<div class="{{{ $column['field'] }}}"
     title="{{{ trans('validation.model_attributes.order.status.' . $resource->{$column['field']}) }}}">
<span @if ($resource->{$column['field']} == \App\Models\OrderStatusConstants::NOVEL)
    class="text-success" @endif >
    {{{ trans('validation.model_attributes.order.status.' . $resource->{$column['field']}) }}}
</span>
</div>
