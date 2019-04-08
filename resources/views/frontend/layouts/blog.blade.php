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
    <div class="page-cover">
        <h3 class="page-cover__title">Our Blog</h3>
    </div>
    <div class="ss-container">
        <div class="content-wrap">
            @include('frontend.partials.blog.sidebar')
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@include('frontend.includes.php_js')
@include('frontend.includes.scripts')
</body>
</html>
