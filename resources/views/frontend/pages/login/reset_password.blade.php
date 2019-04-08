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
                    <h3 class="already__title">Reset your password </h3>
                    @if(!isset($expired))
                        <p class="already__desc">
                        </p>
                        @if(session()->has('message') && session()->has('class'))
                            @if(session('class')=='danger')
                                <p class="already__desc error">{!! session('message') !!}</p>
                            @elseif(session('class')=='success')
                                <p class="already__desc success">{!! session('message') !!}</p>
                            @endif
                        @endif
                        {!!Form::open(['route'=>'password.request','class'=>'already__form'])!!}

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        {!!Form::password('password', ['placeholder'=>'Password'])!!}
                        @if($errors->has('password'))
                            <p class="help-block error">{!!$errors->first('password')!!}</p>
                        @endif

                        {!!Form::password('password_confirmation', ['placeholder'=>'Confirm Password'])!!}
                        @if($errors->has('password_confirmation'))
                            <p class="help-block error">{!!$errors->first('password_confirmation')!!}</p>
                        @endif

                        <div class="question-box">
                            @if($current_question!=null)
                                {!!Form::label('answer',$current_question->questions)!!}
                                {!!Form::text('answer', old('answer'), ['class'=>'form-control','placeholder'=>'Answer'])!!}
                                @if($errors->has('answer'))
                                    <p class="help-block">{!!$errors->first('answer')!!}</p>
                                @endif
                            @endif
                        </div>
                        <button type="submit" class="button button--accent">Reset</button>
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
@stop

@section('contentFooter')

@stop
