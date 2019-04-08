@extends('frontend.layouts.membership')

@section('contentHeader')
    <style>

        .form input {
            padding: 5px 14px;
            border: 1px solid #d5d9da;
            width: 272px;
            font-size: 1em;
            height: 25px;
        }

        #result {
            font-size: 9px;
        }

        .col-md-3.short {
            margin-left: 1px;
            background: #FF0000;
            height: 10px;
        }

        .short {
            font-weight: bold;
            color: #FF0000;
            font-size: larger;
        }

        .col-md-3.weak {
            margin-left: 1px;
            background: orange;
            height: 10px;
        }

        .weak {
            font-weight: bold;
            color: orange;
            font-size: larger;
        }

        .col-md-3.good {
            margin-left: 1px;
            background: #2D98F3;
            height: 10px;
        }

        .good {
            font-weight: bold;
            color: #2D98F3;
            font-size: larger;
        }

        .col-md-3.strong {
            margin-left: 1px;
            background: limegreen;
            height: 10px;
        }

        .strong {
            font-weight: bold;
            color: limegreen;
            font-size: larger;
        }
        #newsletter_checkbox {
            width:20px;
            height:20px;
        }
        #terms_checkbox {
            width:20px;
            height:20px;
        }
    </style>
@stop

@section('content')
    <div class="ss-container">
        <h2 class="membership__title-sm">Member Info & Billing Address</h2>
        <p class="membership__text">By filling this registration form you are confirming that you have read and
            agreed to our  <a target="_blank" rel="noopener noreferrer" href="{!! url('/terms-trade') !!}" title="Terms of Trade"> Terms of Trade.</a>
        </p>
        <p class="membership__text">
          <b>Signup to our newsletter for updates and other important information</b>
          <input type="checkbox" id="newsletter_checkbox">
        </p>
        <p class="membership__text"> <span>I Accept</span> <input type="checkbox" id="terms_checkbox"></p>

        {!!Form::open(['route'=>'registration.store','id'=>'registartion_form'])!!}
        <div class="membership-box membership--box50 membership--top30">
            <div class="memebership__box">
                <div class="form">
                    <h2 class="form__title">Personal Details</h2>
                    <ul class="ul-reset form__box">
                        <li>
                            <div class="form__element">
                                {!!Form::email('email', old('email'), ['placeholder'=>'Email Address *','id'=>'email_id'])!!}
                                @if($errors->has('email'))
                                    <p class="help-block error">{!!$errors->first('email')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li class="form__box__50">
                            <div class="form__element">
                                <div class="password">
                                    <ul class="ul-reset password__message">
                                    </ul>
                                    {!!Form::password('password', ['placeholder'=>'Password *','id'=>'password'])!!}
                                    <span class="password__info">
                                        <i class="material-icons">info</i>
                                    </span>
                                </div>
                                <span id="result"></span>
                                @if($errors->has('password'))
                                    <p class="help-block error">{!!$errors->first('password')!!}</p>
                                @endif
                            </div>
                            <div class="form__element">
                                {!!Form::password('password_confirmation', ['placeholder'=>'Retype Password *'])!!}
                                @if($errors->has('password_confirmation'))
                                    <p class="help-block error">{!!$errors->first('password_confirmation')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li class="form__box__50">
                            <div class="form__element">
                                {!!Form::text('first_name', old('first_name'), ['placeholder'=>'First Name *'])!!}
                                @if($errors->has('first_name'))
                                    <p class="help-block error">{!!$errors->first('first_name')!!}</p>
                                @endif
                            </div>
                            <div class="form__element">
                                {!!Form::text('last_name', old('last_name'), ['placeholder'=>'Last Name *'])!!}
                                @if($errors->has('last_name'))
                                    <p class="help-block error">{!!$errors->first('last_name')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="form__element">
                                {!!Form::select('gender',$genders,old('gender'),['placeholder'=>'Gender *'])!!}
                                @if($errors->has('gender'))
                                    <p class="help-block error">{!!$errors->first('gender')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="form__element">
                                {!!Form::text('company_name', old('company_name'), ['placeholder'=>'Company Name'])!!}
                                @if($errors->has('company_name'))
                                    <p class="help-block error">{!!$errors->first('company_name')!!}</p>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="memebership__box">
                <div class="form">
                    <h2 class="form__title">Contact Details</h2>
                    <ul class="ul-reset form__box">
                        <li>
                            <div class="form__element">
                                {!!Form::text('cd_address', old('cd_address'), ['placeholder'=>'Address *'])!!}
                                @if($errors->has('cd_address'))
                                    <p class="help-block error">{!!$errors->first('cd_address')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="form__element">
                                <div class="ss-select-wrap">
                                    {!!Form::select('cd_country',$countries,session('location')->iso_code,['class'=>'ss-select','placeholder'=>'Select Country *','id'=>'country'])!!}
                                </div>
                                @if($errors->has('cd_country'))
                                    <p class="help-block error">{!!$errors->first('cd_country')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li class="form__box__50">
                            <div class="form__element">
                                {!!Form::text('cd_city', old('cd_city'), ['placeholder'=>'City *'])!!}
                                @if($errors->has('cd_city'))
                                    <p class="help-block error">{!!$errors->first('cd_city')!!}</p>
                                @endif
                            </div>
                            <div class="form__element">
                                {!!Form::text('cd_postalcode', old('cd_postalcode'), ['placeholder'=>'Postal Code'])!!}
                                @if($errors->has('cd_postalcode'))
                                    <p class="help-block error">{!!$errors->first('cd_postalcode')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="form__element">
                                {!!Form::text('cd_state', old('cd_state'), ['placeholder'=>'State of Province *'])!!}
                                @if($errors->has('cd_state'))
                                    <p class="help-block error">{!!$errors->first('cd_state')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li class="form__box__50">
                            <div class="form__element">
                                {!!Form::select('cd_country_code', $country_codes, session('location')->iso_code,['placeholder'=>'Country Code *','id'=>'country_code'])!!}
                                @if($errors->has('cd_country_code'))
                                    <p class="help-block error">{!!$errors->first('cd_country_code')!!}</p>
                                @endif
                            </div>
                            <div class="form__element">
                                {!!Form::text('cd_phone', old('cd_phone'), ['placeholder'=>'Phone Number *','id'=>'phone_no'])!!}
                                @if($errors->has('cd_phone'))
                                    <p class="help-block error">{!!$errors->first('cd_phone')!!}</p>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="membership__seperator">
            <p>*Required fields</p>
        </div>
        <div class="membership-box">
            <div class="memebership__box">
                <div class="form form--50">
                    <h2 class="form__title">Billing Address</h2>
                    <div class="check-box">
                        <input type="checkbox" name="same_as_cd" id="information" value="1"
                               @if(old('same_as_cd')==1)
                               checked
                                @endif
                        />
                        <label for="information">Use same information as contact details</label>
                    </div>
                    <ul class="ul-reset form__box form-Billing billingAddressDetails">
                        <li>
                            <div class="form__element">
                                {!!Form::text('ba_address', old('ba_address'), ['placeholder'=>'Address *'])!!}
                                @if($errors->has('ba_address'))
                                    <p class="help-block error">{!!$errors->first('ba_address')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="form__element">
                                {!!Form::text('ba_state', old('ba_state'), ['placeholder'=>'State of province *'])!!}
                                @if($errors->has('ba_state'))
                                    <p class="help-block error">{!!$errors->first('ba_state')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="form__element">
                                <div class="ss-select-wrap">
                                    {!!Form::select('ba_country',$countries,old('ba_country'),['class'=>'ss-select','placeholder'=>'Select Country *'])!!}
                                </div>
                                @if($errors->has('ba_country'))
                                    <p class="help-block error">{!!$errors->first('ba_country')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li class="form__box__50 form__box--50">
                            <div class="form__element">
                                {!!Form::select('ba_country_code',$country_codes,old('ba_country_code'),['placeholder'=>'Country Code *'])!!}
                                @if($errors->has('ba_country_code'))
                                    <p class="help-block error">{!!$errors->first('ba_country_code')!!}</p>
                                @endif
                            </div>
                            <div class="form__element">
                                {!!Form::text('ba_phone', old('ba_phone'), ['placeholder'=>'Phone Number *'])!!}
                                @if($errors->has('ba_phone'))
                                    <p class="help-block error">{!!$errors->first('ba_phone')!!}</p>
                                @endif
                            </div>
                        </li>
                        <li class="form__box__50">
                            <div class="form__element">
                                {!!Form::text('ba_city', old('ba_city'), ['placeholder'=>'City *'])!!}
                                @if($errors->has('ba_city'))
                                    <p class="help-block error">{!!$errors->first('ba_city')!!}</p>
                                @endif
                            </div>
                            <div class="form__element">
                                {!!Form::text('ba_postalcode', old('ba_postalcode'), ['placeholder'=>'Postal Code'])!!}
                                @if($errors->has('ba_postalcode'))
                                    <p class="help-block error">{!!$errors->first('ba_postalcode')!!}</p>
                                @endif
                            </div>
                        </li>
                    </ul>
                    <div class="membership__action">
                        <button type="submit" class="button button--accent">next</button>
                    </div>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
    </div>
@stop

@section('contentFooter')
    <script>
        $(document).ready(function () {
            usersProfile.init();

            $('#registartion_form').on('submit',function(e){
              e.preventDefault();

              if($("#terms_checkbox").prop('checked') == true){
                 $( "#registartion_form" )[0].submit();
              } else {
                alert('You must accept our terms of trade in order to proceed.');
              }

            });
        });

        $(document).ready(function () {
            $('#password').keyup(function () {
                if ($('#password').val() != '') {
                    result = checkStrength($('#password').val());
                    $('#result').html(result['html']);
                    $('.password__message').html(result['str']);
                    $('#result').show();
                    if(result['str']!=''){
                        $('.password__message').show();
                    }else{
                        $('.password__message').hide();
                    }
                } else {
                    $('#result').hide();
                    $('.password__message').hide();
                }
            });
            $('#password').blur(function(){
                $('#result').hide();
                $('.password__message').hide();
            });

            function hasLowerCase(str) {
                return (/[a-z]/.test(str));
            }

            function hasUpperCase(str) {
                return (/[A-Z]/.test(str));
            }

            function checkStrength(password) {
                if (password != '') {
                    var strength = 0;
                    var html = '';
                    var message = {
                        short: '<li>The length should be minimum 8 characters.</li>',
                        uppercase: '<li>The password must contain one uppercase character.</li>',
                        lowercase: '<li>The password must contain one lowercase character.</li>',
                        special: '<li>The password must contain one special character.</li>',
                        digits: '<li>The password must contain one numeric character.</li>',
                    };
                    if (password.length >= 8) {
                        strength += 1;
                        message.short = '';
                    }
                    if (hasUpperCase(password)) {
                        strength += 1;
                        message.uppercase = '';
                    }
                    if (hasLowerCase(password)) {
                        strength += 1;
                        message.lowercase = '';
                    }
                    if (password.search(/[0-9]/) > 0) {
                        strength += 1;
                        message.digits = '';
                    }
                    var format = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
                    if (format.test(password)) {
                        strength += 1;
                        message.special = '';
                    }
                    if (strength <= 1) {
                        $('#result').removeClass();
                        $('#result').addClass('short');
                        html = '<div class="short-box">' +
                            '<div class="short"></div>' +
                            '<div class=""></div>' +
                            '<div class=""></div>' +
                            '<div class=""></div>' +
                            '<div class=""></div>' +
                            '</div>';
                    } else if (strength == 2) {
                        $('#result').removeClass();
                        $('#result').addClass('weak');
                        html = '<div class="short-box">' +
                            '<div class="weak"></div>' +
                            '<div class="weak"></div>' +
                            '<div class=""></div>' +
                            '<div class=""></div>' +
                            '<div class=""></div>' +
                            '</div>';
                    } else if (strength == 3) {
                        $('#result').removeClass();
                        $('#result').addClass('good');
                        html = '<div class="short-box">' +
                            '<div class="good"></div>' +
                            '<div class="good"></div>' +
                            '<div class="good"></div>' +
                            '<div class=""></div>' +
                            '<div class=""></div>' +
                            '</div>';
                    } else if (strength == 4) {
                        $('#result').removeClass();
                        $('#result').addClass('good');
                        html = '<div class="short-box">' +
                            '<div class="good"></div>' +
                            '<div class="good"></div>' +
                            '<div class="good"></div>' +
                            '<div class="good"></div>' +
                            '<div class=""></div>' +
                            '</div>';
                    } else if (strength == 5) {
                        $('#result').removeClass();
                        $('#result').addClass('strong');
                        html = '<div class="short-box">' +
                            '<div class="strong"></div>' +
                            '<div class="strong"></div>' +
                            '<div class="strong"></div>' +
                            '<div class="strong"></div>' +
                            '<div class="strong"></div>' +
                            '</div>';
                    }
                    str = '';
                    for (var index in message) {
                        str += message[index];
                    }
                    data=[];

                    data['html'] = html;
                    data['str'] = str;
                    return data;
                } else {
                    return '';
                }
            }
        });
    </script>
@stop
