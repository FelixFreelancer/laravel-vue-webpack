<!DOCTYPE html >
<!--📣 : Features-->
<!--📣 : Ratings-->
<!--📣 : Ratings-->
<!--📣 : Features-->
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    @include('frontend.includes.head')
    @yield('contentHeader')
	
</head>
<body>
@include('frontend.includes.header')
<div class="wrap">
    @yield('content')
</div>
@include('frontend.includes.footer')
@include('frontend.includes.php_js')
@include('frontend.includes.scripts')
</body>
</html>
