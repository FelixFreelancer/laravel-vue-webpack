@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <div class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <h3 class="dashboard__content__title">Subscription plan</h3>
        <div class="sub-wrap">
            <p class="success" id="auto_renew_message" style="display: none;"></p>
            <div class="sub-plan"><span class="sub-wrap__title">Your subscribed plan</span>
                <div class="auto-renew">
                    <div class="sc">
                        @if(auth()->user()->plan_type=='paid')
                            <img src="{!! asset('img/premium.png') !!}"/>
                            <div class="sc__type">
                                <span class="type">Premium Member</span>
                                <span class="started">
                                    (started: {!! clientShowingDate(auth()->user()->started_at) !!})
                                </span>
                            </div>
                        @else
                            <img src="{!! asset('img/free.png') !!}"/>
                            <div class="sc__type">
                                <span class="type">Free Member</span>
                                <span class="started">
                                    @if(auth()->user()->started_at!=null)
                                        (started: {!! clientShowingDate(auth()->user()->started_at) !!})
                                    @else
                                        (started: {!! clientShowingDate(auth()->user()->created_at) !!})
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                    @if(auth()->user()->plan_type=='paid')
                        <div class="ar">
                            @if(auth()->user()->auto_renew==0)
                                <input type="checkbox" value="1" id="auto_renew"/>
                            @else
                                <input type="checkbox" value="1" id="auto_renew" checked/>
                            @endif
                            <label for="auto_renew">
                                <span>Auto renew every month</span>
                                <span class="ar__next">
                                    (Next bill {!!clientShowingDate(auth()->user()->membership_validity) !!})
                                </span>
                            </label>
                        </div>
                    @endif
                </div>
                @if(auth()->user()->plan_type=='paid')
                    {!!Form::open(['route'=>['users.downgrade']])!!}
                    <button class="button button--accent button--small">Downgrade plan</button>
                    {!!Form::close()!!}
                @else
                    @if(auth()->user()->membership_validity>date_time_database('now'))
                        <p class="help-block">
                            Your premium plan is valid
                            upto {!! clientShowingDate(auth()->user()->membership_validity) !!}.
                        </p>
                    @endif
                    {!!Form::open(['route'=>['users.payment',auth()->user()->id]])!!}
                    {!! Form::hidden('plan_type','paid') !!}
                    <button class="button button--accent button--small">Upgrade plan</button>
                    {!!Form::close()!!}
                @endif
            </div>
            <div class="paym-wrap"><span class="sub-wrap__title">Your Payment Method</span>
                <div class="paym">
                    <div class="paym__item"><span class="paym__title">Credit card</span><img
                                src="{!! asset('img/paym-visa.png') !!}"/>
                    </div>
                    <div class="paym__item"><span class="paym__title">Card Number</span><span class="paym__value">••••••••• 1234</span>
                    </div>
                    <div class="paym__item"><span class="paym__title">Expiry Date</span><span
                                class="paym__value">12/12</span></div>
                    <div class="paym__item">
                        <button class="button button--accent button--small">Change payment method</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('contentFooter')
    <script>
        $(document).on('change', '#auto_renew', function () {
            var status = $('#auto_renew:checked').val();
            if (status != 1) {
                status = 0;
            }
            $.ajax({
                dataType: 'json',
                method: 'post',
                url: ajaxURL + 'users/auto-renew',
                data: {
                    status: status
                },
                success: function (data) {
                    var message = 'Something went wrong. Please try again later.';
                    var add = 'error';
                    var remove = 'success';
                    if (data['status']) {
                        add = 'success';
                        remove = 'error';
                        if (status == 0) {
                            message = 'Auto Renew options disabled successfully.';
                        } else {
                            message = 'Auto Renew options enabled successfully.';
                        }
                    }
                    $('#auto_renew_message').addClass(add).removeClass(remove).text(message).show();
                }
            });
        });
    </script>
@stop