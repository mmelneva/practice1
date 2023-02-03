<div class="{{{ $column['field'] }}}">
    <a href="{{{ wrap_with_paginator(action($resource_controller . '@getEdit', [$resource->id]), isset($resource_list) ? $resource_list : null) }}}"
       title="{{{ $resource->{$column['field']} }}}">
        @if(trim($resource->{$column['field']}))
            {{{ $resource->{$column['field']} }}}
        @else
            <i>{{{ trans('validation.attributes.not_specified') }}}</i>
        @endif
    </a>
</div>