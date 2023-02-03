{{-- Category list for menu --}}

<ul>
    @foreach ($categoryTree as $category)
        <li>
            <div class="menu-element {{{ (isset($current_category) && $current_category->id == $category['element']->id) ? 'active' : ''}}}">
                <div class="name">
                    <a href="{{{ action('App\Controllers\Admin\CatalogProductsController@getIndex', [$category['element']->id]) }}}"
                       style="margin-left: {{{ $lvl * 0.5 }}}em;"
                       class="arrowed"
                       title="{{{ $category['element']->name }}}">
                        @if ($category['hasChildren'])
                            @if (count($category['children']) > 0)
                                <span class="menu-arrow glyphicon glyphicon-triangle-bottom"></span>
                            @else
                                <span class="menu-arrow glyphicon glyphicon-triangle-right"></span>
                            @endif
                        @else
                            <span class="menu-arrow"></span>
                        @endif
                        {{{ $category['element']->name }}}
                    </a>
                </div>
                <div class="control">
                    @include('admin.catalog_categories._category_control_block', ['category' => $category['element']])
                </div>
            </div>
            @if (count($category['children']) > 0)
                @include('admin.catalog_categories._category_list_menu', array('categoryTree' => $category['children'], 'lvl' => $lvl + 3))
            @endif
        </li>
    @endforeach
</ul>