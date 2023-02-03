<!DOCTYPE html>
<html lang="en">
<head>@include('admin.layouts._head')</head>

<body class="guest-container">

<div class="content-wrapper">
    <section class="well" @yield('container_attributes')>
        <h1>@yield('title')</h1>

        @include('admin.layouts._alerts')

        @yield('content')
    </section>
</div>
@include('admin.layouts._footer')

</body>
</html>