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
                    <h3 class="already__title">{!! $title or 'User Verification' !!} </h3>
                    <!-- <p class="already__desc">
                        By Signing in you are confirming that you have read and agreed to our<a href="javacript:;">Terms
                            and Agreement. </a>
                    </p> -->
                    <p class="already__desc">{!! $message !!}</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('contentFooter')
@stop
