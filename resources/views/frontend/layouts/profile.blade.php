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
<body @if(auth()->check() && auth()->user()->cd_phone_verified_at==null && auth()->user()->email_verified_at!=null) class="overlay-cover" @endif>
  @if(auth()->check() && auth()->user()->cd_phone_verified_at==null && auth()->user()->email_verified_at!=null)
    <div class="disable-success">
      <div class="ss-container">
        <div class="action-text">
            <i class="material-icons" style="color: #fff;font-size: 30px;margin-right: 10px;">phonelink_erase</i>
            <p>Your account is pre-activated. Please <a  class="link-text-unverified" href="{!! url()->route('users.verification.mobile',['user'=>auth()->user()->id]) !!}">verify your phone</a> number now to fully activate your account.</p>
        </div>
        <div class="action-bar">
            <!--<a class="link-text-unverified btn-action btn-action-fill" href="{!! url()->route('users.verification.mobile',['user'=>auth()->user()->id]) !!}">Verify Now</a>-->
            <a href="{!! url()->route('logout') !!}" class="btn-action">Log out</a>
        </div>
      </div>
  </div>
    @endif
    @if(isset($message))
    <p class="success">{!! $message !!} </p>
    @endif
@include('frontend.includes.header')

<div class="wrap">
    <div class="dashboard section-first">
        <div class="dashboard__cover">
            <h3 class="dashboard__title">Customer Dashboard</h3>
        </div>
        <div class="ss-container dashboard__customer">
            @include('frontend.partials.profile.user_info')
            @yield('content')
        </div>
    </div>
</div>
@include('frontend.popups.profile_pic')
@include('frontend.includes.footer')
@include('frontend.includes.php_js')
@include('frontend.includes.scripts')
@yield('contentFooter')
</body>
</html>
