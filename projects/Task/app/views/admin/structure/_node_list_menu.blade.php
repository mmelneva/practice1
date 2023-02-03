{{-- Node list for menu --}}

<ul>
    @foreach ($nodeTree as $node)
        <li>
            <div class="menu-element {{{ (isset($current_node) && $current_node->id == $node['element']->id) ? 'active' : ''}}}">
                <div class="name">
                    <a href="{{{ TypeContainer::getContentUrl($node['element']) }}}"
                       style="margin-left: {{{ $lvl * 0.5 }}}em;"
                       class="arrowed"
                       title="{{{ $node['element']->name }}}">
                        @if ($node['hasChildren'])
                            @if (count($node['children']) > 0)
                                <span class="menu-arrow glyphicon glyphicon-triangle-bottom"></span>
                            @else
                                <span class="menu-arrow glyphicon glyphicon-triangle-right"></span>
                            @endif
                        @else
                            <span class="menu-arrow"></span>
                        @endif
                        {{{ $node['element']->name }}}
                    </a>
                </div>
                <div class="control">
                    @include('admin.structure._node_control_block', ['node' => $node['element']])
                </div>
            </div>
            @if (count($node['children']) > 0)
                @include('admin.structure._node_list_menu', array('nodeTree' => $node['children'], 'lvl' => $lvl + 3, 'current_node' => isset($current_node) ? $current_node : null))
            @endif
        </li>
    @endforeach
</ul>