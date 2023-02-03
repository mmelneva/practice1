{{-- Default controls for element list --}}

<a class="glyphicon glyphicon-pencil"
   title="{{{ trans('interactions.edit') }}}"
   href="{{{ wrap_with_paginator(action($controller . '@getEdit', [$element->id]), isset($resource_list) ? $resource_list : null) }}}"></a>

@if (isset($disable_delete) && $disable_delete)
   <span class="glyphicon glyphicon-trash" title="{{{ trans('alerts.delete_is_disallowed') }}}"></span>
@else
   <a class="glyphicon glyphicon-trash"
      title="{{{ trans('interactions.delete') }}}"
      data-method="delete"
      data-confirm="{{{ isset($delete_confirm) ? $delete_confirm : trans('interactions.delete_confirm') }}}"
      href="{{{ wrap_with_paginator(action($controller . '@deleteDestroy', [$element->id]), isset($resource_list) ? $resource_list : null) }}}"></a>
@endif