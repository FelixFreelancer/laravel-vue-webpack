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
	console.log(otpExpiresInSeconds);
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

                    <p class="already__desc otpViaCall"></p>
                    @if(session()->has('message') && session()->has('class'))
                        @if(session('class')=='danger')
                            <p class="already__desc error">{!! session('message') !!}</p>
                        @elseif(session('class')=='success')
                            <p class="already__desc success">{!! session('message') !!}</p>
                        @endif
                    @endif
                    {!! Form::open(['route'=>['users.verification.mobile.check',$user->id],'class'=>'already__form']) !!}
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
                </div>
            </div>
        </div>
    </div>
    @include('frontend.popups.mobile_confirmation')
    @include('frontend.popups.change_mobile_number')
@stop

@section('contentFooter')
<script type="text/javascript"  src="{!! asset('js/otp.js') !!}"></script>
    <script>
	  	console.log(otpExpires);
      otp.init();
      $(document).on('click',".js_contact_us",function(e){
        e.preventDefault();
        window.location.href="{!! url('/contact-us?inquiry=true') !!}";
      });
    </script>
@stop
