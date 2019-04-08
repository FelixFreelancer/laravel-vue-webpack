<!DOCTYPE html >
<!--📣 : Features-->
<!--📣 : Ratings-->
<!--📣 : Ratings-->
<!--📣 : Features-->
<html lang="en">
<head>
    @include('frontend.includes.head')
    @yield('contentHeader')

</head>
<body>
@include('frontend.includes.header')
<div class="wrap">
    <div class="membership section-first">
        @include('frontend.partials.membership.membership_head')
        <div class="membership__content">
            @yield('content')
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@include('frontend.includes.php_js')
@include('frontend.includes.scripts')
@yield('contentFooter')
</body>
</html>
