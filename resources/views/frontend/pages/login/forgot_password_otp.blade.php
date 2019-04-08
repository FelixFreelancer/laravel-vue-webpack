@extends('frontend.layouts.dashboard')

@section('contentHeader')
<script type="text/javascript">
	var user_id = '{!! $user->id or ''!!}';
	var otpExpires = "{!! config('site.otp_disable') !!}";
	var callExpires = "{!! config('site.call_disable') !!}";
	var mobile_number = "{!! $user['cd_phone']  or ''!!}";
	var code = "{!! $user['cd_country_code'] or '1' !!}";
	var otpExpiresInSeconds = (otpExpires*60) * 1000;
	var callExpiresInSeconds = (callExpires*60) * 1000;
</script>
@stop

@section('content')
    <div class="signin-wrap">
        <div class="signin-background"></div>
        <div class="ss-container signin">
            <div class="signin__new">
                <p>
                    Welcome to GlobalParcelForward.com
                </p>
                <p>
                    Shoppers around the world use our service to forward parcels globally. We are the only parcel
                    forwarding company that has the resources, expertise, customer dedication and global experience to
                    make shopping and shipping from the UK fast, dependable, reliable and affordable.
                </p>
                <div class="register">
                    <h3 class="register__title">Not a member?</h3>
                    <p class="register__desc">Register for free and start shipping today!</p>
                    <a class="button button--small button--white" href="{!! url()->route('registration.plan') !!}">Register</a>
                </div>
            </div>
            <div class="signin__already">
                <div class="already">
                    <h3 class="already__title">Validate your one time pass-code</h3>
                    <p class="already__desc">  As a security measure to protect your account from unauthorized changes, we have sent
  a seven digits one time pass-code to the registered phone number on your account.
  Please input the code below to continue with your password reset</p>


                      @if(!isset($expired))
                      <p class="already__desc otpViaCall"></p>
                        @if(session()->has('message') && session()->has('class'))
                            @if(session('class')=='danger')
                                <p class="already__desc error">{!! session('message') !!}</p>
                            @elseif(session('class')=='success')
                                <p class="already__desc success">{!! session('message') !!}</p>
                            @endif
                        @endif
                        {!!Form::open(['url'=>'validate-otp','class'=>'already__form','id'=>'otp_form'])!!}
                          <input type="hidden" name="token" value="{{ $token }}">
                          <input type="hidden" name="email" value="{{ $email }}">
                          <div class="code__input">
                            <input data-id="0" type="number" maxlength="1" name="otp[0]"/>
                            <input data-id="1" type="number" maxlength="1" name="otp[1]"/>
                            <input data-id="2" type="number" maxlength="1" name="otp[2]"/>
                            <input data-id="3" type="number" maxlength="1" name="otp[3]"/>
                            <input data-id="4" type="number" maxlength="1" name="otp[4]"/>
                            <input data-id="5" type="number" maxlength="1" name="otp[5]"/>
                            <input data-id="6" type="number" maxlength="1" name="otp[6]"/>
                            <div class="code__btn">
                                <button class="button button--accent" type="submit">
                                    <i class="fas fa-angle-right fa-3x"></i>
                                </button>
                                <button class="button button--text" type="reset">Reset</button>
                            </div>
                        </div>
                        @if($errors->has('otp.0')||$errors->has('otp.1')||$errors->has('otp.2')||$errors->has('otp.3')||$errors->has('otp.4')||$errors->has('otp.5')||$errors->has('otp.6'))
                            <div class="mt-3">
                                <p class="help-block error form__error">Please enter correct otp.</p>
                            </div>
                        @endif
                        {!! Form::close() !!}
                    @else
                        <p class="already__desc">
                            Your reset password link is already expired. Please <a
                                    href="{!! url()->route('password.request') !!}"> click here</a> for resending it.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
      @if(!isset($expired))
      @include('frontend.popups.mobile_confirmation')
      @include('frontend.popups.change_mobile_number')
  @endif
@stop

@section('contentFooter')
<script type="text/javascript"  src="{!! asset('js/otp.js') !!}"></script>
<script>

    $(document).on('submit', '#otp_form', function (e) {
        e.preventDefault();
        var token  = '{!! $token or '' !!}';
        $.ajax({
            dataType: 'json',
            method: 'post',
            data: $("#otp_form").serialize(),
            url: siteURL + "validate-otp",
            success: function (data) {
              if(data['status']){
                window.location.href = siteURL + 'password/reset/' + token;
              }else{
                $(".otpViaCall").addClass(data['class']);
                $(".otpViaCall").html(data['message']);
              }
            }
        });
    });

    otp.init();
</script>
@stop
