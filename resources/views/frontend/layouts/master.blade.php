<!DOCTYPE html>
<html>
<head>

    @include('frontend.includes.head')
    @yield('contentHeader')

</head>
<body class="demo-body">
<div class="demo-links">
    @yield('content')
</div>
@include('frontend.includes.php_js')
@include('frontend.includes.scripts')
@yield('contentFooter')
</body>
</html>
