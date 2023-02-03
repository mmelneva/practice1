<ul class="element-list" data-sortable-group="">
    @foreach ($productList as $product)
        <li data-element-id="{{{ $product->id }}}">
            <div class="element-container">
                @include('admin.resource_list_sortable._list_sorting_controls')
                <div class="name">
                    <a href="{{{ action('App\Controllers\Admin\CatalogProductsController@getEdit', [$product->id]) }}}">{{{ $product->name }}}</a>
                </div>
                <div class="position">{{{ $product->position }}}</div>
                @include('admin.shared._list_flag', ['element' => $product, 'action' => action('App\Controllers\Admin\CatalogProductsController@putToggleAttribute', [$product->id, 'publish']), 'attribute' => 'publish'])
                <div class="control">
                    <a class="glyphicon glyphicon-pencil"
                       title="{{{ trans('interactions.edit') }}}"
                       href="{{{ action('App\Controllers\Admin\CatalogProductsController@getEdit', [$product->id]) }}}"></a>
                    <a class="glyphicon glyphicon-trash"
                       title="{{{ trans('interactions.delete') }}}"
                       data-method="delete"
                       data-confirm="Вы уверены, что хотите удалить данный товар?"
                       href="{{{ action('App\Controllers\Admin\CatalogProductsController@deleteDestroy', [$product->id]) }}}"></a>
                </div>
            </div>
        </li>
    @endforeach
</ul>