{{-- One level of main menu --}}
<ul>
    @foreach ($menu_lvl as $menu_element)
        @if (isset($menu_element['elements']))
            <li class="element-group-wrapper @if ($menu_element['active']) active @endif">
                <span class="menu-element">
                    <span class="decoration">{{{ $menu_element['name'] }}}</span>
                    <span class="glyphicon {{{ $menu_element['icon'] }}}" title="{{{ $menu_element['name'] }}}" data-toggle="tooltip" data-placement="right"></span>
                </span>

                <div class="element-group">
                    @include('admin.layouts._main_menu_lvl', ['menu_lvl' => $menu_element['elements']])
                </div>
            </li>
        @else
            <li>
                <a href="{{{ $menu_element['link'] }}}" class="menu-element @if ($menu_element['active']) active @endif">
                    <span class="text">{{{ $menu_element['name'] }}}</span>
                    <span class="glyphicon {{{ $menu_element['icon'] }}}" title="{{{ $menu_element['name'] }}}" data-toggle="tooltip" data-placement="right"></span>
                </a>
            </li>
        @endif
    @endforeach
</ul>