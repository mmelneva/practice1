{{-- Control block for exact node in list --}}

<a class="glyphicon glyphicon-pencil"
   title="{{{ trans('interactions.edit') }}}"
   href="{{{ TypeContainer::getContentUrl($node) }}}"></a>
<a class="glyphicon glyphicon-wrench"
   title="{{{ trans('interactions.properties') }}}"
   href="{{{ action('App\Controllers\Admin\StructureController@getEdit', [$node->id]) }}}"></a>
<a class="glyphicon glyphicon-trash"
   title="{{{ trans('interactions.delete') }}}"
   data-method="delete"
   data-confirm="Вы уверены, что хотите удалить данную страницу?"
   href="{{{ action('App\Controllers\Admin\StructureController@deleteDestroy', [$node->id]) }}}"></a>