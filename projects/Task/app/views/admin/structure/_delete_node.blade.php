{{-- Buttton to delete node --}}

<a class="btn btn-danger"
   data-method="delete"
   data-confirm="Вы уверены, что хотите удалить данную страницу?"
   href="{{{ action('App\Controllers\Admin\StructureController@deleteDestroy', $node->id) }}}">{{{ trans('interactions.delete') }}}</a>