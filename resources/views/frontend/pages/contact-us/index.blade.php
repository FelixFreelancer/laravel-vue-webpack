@extends('frontend.layouts.common')

@section('contentHeader')

@stop

@section('content')
    <div class="page-cover">
        <h1 class="page-cover__title">Contact Us</h1>
    </div>
    <div class="contact-us">
      @include('frontend.partials.privacy_cookie')

        <div class="contact-us__map">
            {{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d158858.47482389523!2d-0.242023467071981!3d51.528557833571085!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2sLondon%2C+UK!5e0!3m2!1sen!2sin!4v1520229168457"
                    frameborder="0" style="border:0;" allowfullscreen=""></iframe> --}}
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2424.2005645860577!2d-2.0598378841901446!3d52.584066979826424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870994b6eb2ffcd%3A0x1d4e552ea58fc79b!2sGlobal+Parcel+Forward!5e0!3m2!1sen!2sin!4v1529320172436"  frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <div class="contact-us__details">
            <div class="livechat">
                <ul class="livechat__info ul-reset">
                    <li>
                        <i class="fas fa-clock"></i><span>9:00 am to 5:00 pm EST</span>
                    </li>
                    <li>
                        <a href="https://www.globalparcelforward.com/contact-us" title="Contact Global Parcel Forward">
                            <i class="fas fa-phone"></i><span>+44 1 902 272004</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:support@globalparcelforward.com" title="Email Us">
                            <i class="fas fa-envelope"></i><span>support@globalparcelforward.com</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.globalparcelforward.com/" taget="_blank" title="Global Parcel Forward">
                            <i class="fas fa-globe"></i><span>www.globalparcelforward.com</span>
                        </a>
                    </li>
                </ul>
                <a class="livechat__img" href="https://tawk.to/globalparcelforward" title="Live Chat" target="_blank" rel="nofollow">
                    <img src="{!! asset('img/live-chat.png') !!}" alt="Live Chat"/>
                </a>
            </div>
            {!! Form::model($user,['route'=>'contact-us.store','class'=>'contactform']) !!}
            <h3 class="contactform__title">Contact Form</h3>
            @if(session()->has('message') && session()->has('class'))
                @if(session('class')=='danger')
                    <p class="already__desc error">{!! session('message') !!}</p>
                @elseif(session('class')=='success')
                    <p class="already__desc success">{!! session('message') !!}</p>
                @endif
            @endif
            @if(isset($user['id']))
              <input type="hidden" name="user_id" value="{!! $user['id'] !!}">
            @endif
            <ul class="ul-reset contactform__ul">
                <li>
                    {!!Form::text('name', old('name'), ['class'=>'form-control','placeholder'=>'Your Name'])!!}
                    @if($errors->has('name'))
                        <p class="help-block error">{!!$errors->first('name')!!}</p>
                    @endif
                </li>
                <li class="double">
					<div>
                    {!!Form::email('email', old('email'), ['class'=>'form-control','placeholder'=>'Email'])!!}
                    @if($errors->has('email'))
                        <p class="help-block error">{!!$errors->first('email')!!}</p>
                    @endif
				    </div>
					<div>
                    {!!Form::text('phone_no', old('phone_no'), ['class'=>'form-control','placeholder'=>'Phone Number'])!!}
                    @if($errors->has('phone_no'))
                        <p class="help-block error">{!!$errors->first('phone_no')!!}</p>
                    @endif
				    </div>
                </li>
                <li>
                    {!!Form::text('subject', old('subject'), ['class'=>'form-control','placeholder'=>'Subject'])!!}
                    @if($errors->has('subject'))
                        <p class="help-block error">{!!$errors->first('subject')!!}</p>
                    @endif
                </li>
                <li>
                    {!!Form::label('message','Message')!!}
                    {!!Form::textarea('message', old('message'), ['class'=>'form-control','rows'=>8])!!}
                    @if($errors->has('message'))
                        <p class="help-block error">{!!$errors->first('message')!!}</p>
                    @endif
                </li>
                <li>
                    <button type="submit" class="button button--accent button--large">Send Message</button>
                </li>
            </ul>
            {!!Form::close()!!}
        </div>
    </div>
@stop

@section('contentFooter')

@stop
