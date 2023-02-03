{{-- Control block for exact category in list --}}

<a class="glyphicon glyphicon-pencil"
   title="{{{ trans('interactions.edit') }}}"
   href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@getEdit', [$category->id]) }}}"></a>
<a class="glyphicon glyphicon-list"
   href="{{{ action('App\Controllers\Admin\CatalogProductsController@getIndex', [$category->id]) }}}"
   title="Товары"></a>
<a class="glyphicon glyphicon-trash"
   title="{{{ trans('interactions.delete') }}}"
   data-method="delete"
   data-confirm="Вы уверены, что хотите удалить данную категорию?"
   href="{{{ action('App\Controllers\Admin\CatalogCategoriesController@deleteDestroy', [$category->id]) }}}"></a>