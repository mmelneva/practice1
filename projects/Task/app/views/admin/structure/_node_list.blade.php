<ul class="element-list" data-sortable-group="">
    @foreach ($nodeTree as $node)
        <li data-element-id="{{{ $node->id }}}">
            <div class="element-container">
                @include('admin.resource_list_sortable._list_sorting_controls')
                <div class="name">
                    <a href="{{{ TypeContainer::getContentUrl($node) }}}"
                       style="margin-left: {{{ $lvl * 0.5 }}}em;">{{{ $node->name }}}
                        <?php $pPage = $node->productTypePage; ?>
                        @if (!empty($pPage) && !empty($pPage->products_count) )
                            ({{ $pPage->products_count }}, опубликовано: {{ $pPage->products_count_published }})
                        @endif
                    </a>

                </div>
                <div class="position">{{{ $node->position }}}</div>
                @include('admin.shared._list_flag', ['element' => $node, 'action' => action('App\Controllers\Admin\StructureController@putToggleAttribute', [$node->id, 'publish']), 'attribute' => 'publish'])
                @include('admin.shared._list_flag', ['element' => $node, 'action' => action('App\Controllers\Admin\StructureController@putToggleAttribute', [$node->id, 'menu_top']), 'attribute' => 'menu_top'])
                {{--@include('admin.shared._list_flag', ['element' => $node, 'action' => action('App\Controllers\Admin\StructureController@putToggleAttribute', [$node->id, 'scrolled_menu_top']), 'attribute' => 'scrolled_menu_top'])--}}
{{--                @include('admin.shared._list_flag', ['element' => $node, 'action' => action('App\Controllers\Admin\StructureController@putToggleAttribute', [$node->id, 'menu_bottom']), 'attribute' => 'menu_bottom'])--}}
                @if($item=$node->productTypePage()->first())
                    @include('admin.shared._list_flag', ['element' => $item, 'action' => action('App\Controllers\Admin\ProductTypePagesController@putToggleAttribute', [$item->id, 'use_reviews_associations']), 'attribute' => 'use_reviews_associations'])
                @else
                    <div class="use_reviews_associations-status toggle-flag-container"></div>
                @endif

                <div class="alias">
                    <a href="{{{ UrlBuilder::getUrl($node) }}}" target="_blank">
                        {{{ TypeContainer::getClientUrl($node, false) }}}
                    </a>
                </div>
                <div class="type">
                    {{{ TypeContainer::getTypeName($node->type) }}}
                </div>
                <div class="control">
                    @include('admin.structure._node_control_block', ['node' => $node])
                </div>
            </div>
            @if (count($node->children) > 0)
                @include('admin.structure._node_list', ['nodeTree' => $node->children, 'lvl' => $lvl + 3])
            @endif
        </li>
    @endforeach
</ul>