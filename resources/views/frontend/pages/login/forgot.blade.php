@extends('frontend.layouts.dashboard')

@section('contentHeader')

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
                    <h3 class="already__title">Forgot password </h3>
                    <p class="already__desc">
                    </p>
                    @if(session()->has('message') && session()->has('class'))
                        @if(session('class')=='danger')
                            <p class="already__desc error">{!! session('message') !!}</p>
                        @elseif(session('class')=='success')
                            <p class="already__desc success">{!! session('message') !!}</p>
                        @endif
                    @endif
                    {!!Form::open(['route'=>'password.email','class'=>'already__form'])!!}

                    {!!Form::text('email', old('email'), ['autocomplete'=>'off','placeholder'=>'Email Address'])!!}
                    @if($errors->has('email'))
                        <p class="help-block error">{!!$errors->first('email')!!}</p>
                    @endif
                    <button type="submit" class="button button--accent">Reset</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('contentFooter')
@stop