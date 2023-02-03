<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layouts._head')
</head>

<body>
    @include('admin.layouts._top_nav')

    <div class="structure-columns-container">
        @include('admin.layouts._main_menu')
        @yield('second_column')

        <div class="content-wrapper main-content">
            <h1>@yield('title')</h1>
            @include('admin.layouts._alerts')
            @yield('content')
        </div>
    </div>

    @include('admin.layouts._footer')
</body>
</html>