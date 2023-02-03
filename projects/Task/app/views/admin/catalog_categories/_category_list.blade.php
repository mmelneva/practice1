<ul class="element-list" data-sortable-group="">
    @foreach ($categoryTree as $category)
        <li data-element-id="{{{ $category->id }}}">
            <div class="element-container">
                @include('admin.resource_list_sortable._list_sorting_controls')
                <div class="name">
                    <a href="{{{ action('App\Controllers\Admin\CatalogProductsController@getIndex', [$category->id]) }}}"
                       style="margin-left: {{{ $lvl * 0.5 }}}em;">{{{ $category->name }}}</a>
                </div>
                <div class="position">{{{ $category->position }}}</div>
                @include('admin.shared._list_flag', ['element' => $category, 'action' => action('App\Controllers\Admin\CatalogCategoriesController@putToggleAttribute', [$category->id, 'publish']), 'attribute' => 'publish'])
                @include('admin.shared._list_flag', ['element' => $category, 'action' => action('App\Controllers\Admin\CatalogCategoriesController@putToggleAttribute', [$category->id, 'top_menu']), 'attribute' => 'top_menu'])
                @include('admin.shared._list_flag', ['element' => $category, 'action' => action('App\Controllers\Admin\CatalogCategoriesController@putToggleAttribute', [$category->id, 'use_reviews_associations']), 'attribute' => 'use_reviews_associations'])
                <div class="alias">{{{ $category->alias }}}</div>
                <div class="control">
                    @include('admin.catalog_categories._category_control_block', ['category' => $category])
                </div>
            </div>
            @if (count($category->children) > 0)
                @include('admin.catalog_categories._category_list', ['categoryTree' => $category->children, 'lvl' => $lvl + 3])
            @endif
        </li>
    @endforeach
</ul>