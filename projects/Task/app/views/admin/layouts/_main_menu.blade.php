{{-- Template to show main menu --}}

<aside class="menu-column @yield('main_menu_class')">
    <div class="menu-wrapper main-menu">
        <div class="menu-toggle">
            <span class="menu-toggle-header content-wrapper">Панель управления</span>
            <span class="btn-container">
                <span class="btn btn-default glyphicon glyphicon-step-backward close-menu"></span>
                <span class="btn btn-default glyphicon glyphicon-step-forward open-menu"></span>
            </span>
        </div>

        @include('admin.layouts._main_menu_lvl', ['menu_lvl' => $main_menu])
    </div>
</aside>
