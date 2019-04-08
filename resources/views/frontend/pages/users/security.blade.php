@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <div class="dashboard__content dashboard__content--pd card-shadow">
        {!!Form::model(auth()->user(),['route'=>'users.security.update','class'=>''])!!}
        <div class="rect"></div>
        <div class="details-wrap">
            <ul class="ul-reset details-ul">
                <li>
                    <h3 class="dashboard__content__title">Password & Security Question</h3>
                </li>
                <li class="gpf-input one-half-wrap">
                    <div class="one-half-input">
                        {!!Form::label('password','New Password')!!}
                        {!!Form::password('password', ['class'=>'form-control','placeholder'=>'New Password'])!!}
                        @if($errors->has('password'))
                            <p class="help-block error">{!!$errors->first('password')!!}</p>
                        @endif
                    </div>
                    <div class="one-half-input">
                        {!!Form::label('password_confirmation','Re-type New Password')!!}
                        {!!Form::password('password_confirmation', ['class'=>'form-control','placeholder'=>'Re-type New Password'])!!}
                        @if($errors->has('password_confirmation'))
                            <p class="help-block error">{!!$errors->first('password_confirmation')!!}</p>
                        @endif
                    </div>
                </li>
                <li class="gpf-input">
                    {!!Form::label('security_question','Security Question')!!}
                    @if(isset($current_question) && $current_question!=null)
                        {!!Form::select('security_question',$security_questions,$current_question->security_question_id,['class'=>'select--security','placeholder'=>'Security Question'])!!}
                    @else
                        {!!Form::select('security_question',$security_questions,old('security_question'),['class'=>'select--security','placeholder'=>'Security Question'])!!}
                    @endif
                    @if($errors->has('security_question'))
                        <p class="help-block error">{!!$errors->first('security_question')!!}</p>
                    @endif
                </li>
                <li class="gpf-input">
                    {!!Form::label('answer','Unique Answer')!!}
                    @if(isset($current_question) && $current_question!=null)
                        {!!Form::text('answer', $current_question->answer, ['class'=>'form-control','placeholder'=>'Unique Answer'])!!}
                    @else
                        {!!Form::text('answer', old('answer'), ['class'=>'form-control','placeholder'=>'Unique Answer'])!!}
                    @endif
                    @if($errors->has('answer'))
                        <p class="help-block error">{!!$errors->first('answer')!!}</p>
                    @endif
                </li>
                <li>
                    <button type="submit" class="button button--accent">Update</button>
                </li>
            </ul>
        </div>
        {!! Form::close() !!}
        <div class="dashboard__footer">
            <h3 class="dashboard__content__title">Advanced Security Settings</h3>
            <div class="two-step">
                <div class="two-step__header">
                    <div class="two-step__txt">
                        <h3 class="two-step__title">Two-Step Verification
                            <a class="two-step__help" href="#nogo" data-tooltip="I’m the tooltip text.">
                                <i class="fas fa-question"></i>
                            </a>
                        </h3>
                        <p class="two-step__desc">This will require your mobile phone to sign in to your
                            account</p>
                    </div>
                    @if(auth()->user()->google2fa_secret==null)
                        <div class="activate">
                            <a class="button button--accent" href="{!! url()->route('users.twofactor.enable') !!}">
                                Activate Now
                            </a>
                        </div>
                    @else
                        <div class="disable">
                            <a class="button button--accent" href="{!! url()->route('users.twofactor.disable') !!}">
                                Disable Now
                            </a>
                        </div>
                    @endif
                </div>
                <ul class="ul-reset steps">
                    <li>
                        <div class="step__img"><img src="img/two-step-1.png"/></div>
                        <p class="step__txt">Enter your password like usual</p>
                    </li>
                    <li><i class="fas fa-long-arrow-alt-right fa-2x"></i></li>
                    <li>
                        <div class="step__img"><img src="img/two-step-2.png"/></div>
                        <p class="step__txt">Authentication code will be sent to your phone</p>
                    </li>
                    <li><i class="fas fa-long-arrow-alt-right fa-2x"></i></li>
                    <li>
                        <div class="step__img"><img src="img/two-step-3.png"/></div>
                        <p class="step__txt">Enter authentication code and complete sign in</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@stop

@section('contentFooter')

@stop