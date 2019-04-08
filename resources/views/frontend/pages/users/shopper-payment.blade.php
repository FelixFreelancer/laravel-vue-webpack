@extends('frontend.layouts.membership')

@section('contentHeader')

@stop

@section('content')
    <div class="ss-container is-fluid">
        <h2 class="membership__title-sm">Payment Details</h2>
        <!-- <p class="membership__text">You will be able to add/change billing addresses after you sign up.</p> -->

        @if(session()->has('message') && session()->has('class'))
            @if(session('class')=='danger')
                <p class="already__desc error">{!! session('message') !!}</p>
            @elseif(session('class')=='success')
                <p class="already__desc success">{!! session('message') !!}</p>
            @endif
        @endif
        {!!Form::open(['route'=>'payment-gateway.index', 'id'=>'payment'])!!}
        <div class="payment-card">
            <div class="payment__card card-shadow">
                <div class="payment__content">
                    <div class="row">
                        <div class="col-md-12">
                            <label>
                                <input type="radio" name="payment_gateway_type" value="2" checked>
                                Pay via credit card / debit card
                            </label>
                            <label><input type="radio" name="payment_gateway_type" value="3"> Pay via Paypal</label>
                        </div>
                    </div>
                    <div class="payment_card">
                        {!!Form::text('card_number', old('card_number'), ['placeholder'=>'Card Number'])!!}
                        @if($errors->has('card_number'))
                            <p class="help-block">{!!$errors->first('card_number')!!}</p>
                        @endif
                        <label>Expiration Date</label>
                        <ul class="payment__form ul-reset">
                            <li class="form__box__30">
                                <div class="form__element">
                                    {!!Form::select('month',$months,old('month'),['placeholder'=>'Month *'])!!}
                                    @if($errors->has('month'))
                                        <p class="help-block">{!!$errors->first('month')!!}</p>
                                    @endif
                                </div>
                                <div class="form__element">
                                    {!!Form::select('year',$years,old('year'),['placeholder'=>'Year *'])!!}
                                    @if($errors->has('year'))
                                        <p class="help-block">{!!$errors->first('year')!!}</p>
                                    @endif
                                </div>
                                <div class="form__element help__wrap">
                                    {!!Form::text('security_code', old('security_code'), ['placeholder'=>'Security Code'])!!}
                                    @if($errors->has('security_code'))
                                        <p class="help-block">{!!$errors->first('security_code')!!}</p>
                                    @endif
                                    <div class="help" data-tooltip="I’m the tooltip text.">?</div>
                                </div>
                            </li>
                            <li>
                                <div class="form__element">
                                    {!!Form::text('cardholder_name', old('cardholder_name'), ['placeholder'=>'Cardholder Name'])!!}
                                    @if($errors->has('cardholder_name'))
                                        <p class="help-block">{!!$errors->first('cardholder_name')!!}</p>
                                    @endif
                                </div>
                            </li>
                            <li>
                                <ul class="ul-reset card-list">
                                    <li><img src="{!! asset('img/card-visa.png') !!}"/></li>
                                    <li><img src="{!! asset('img/master.png') !!}"/></li>
                                    <li><img src="{!! asset('img/discover.png') !!}"/></li>
                                    <li><img src="{!! asset('img/american.png') !!}"/></li>
                                    <li><img src="{!! asset('img/union.png') !!}"/></li>
                                    <li><img src="{!! asset('img/bcard.png') !!}"/></li>
                                    <li><img src="{!! asset('img/ocard.png') !!}"/></li>
                                    <li><img src="{!! asset('img/jcb.png') !!}"/></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="membership__action">
            <input type="hidden" name="entity_id" id="entity_id" value="{!! $quotation->id !!}">
            <input type="hidden" name="payment_type" id="payment_type" value="3">
            <button type="submit" class="button button--accent">Pay Now</button>
        </div>
        {!!Form::close()!!}
    </div>
@stop

@section('contentFooter')
    <script>
        function showPaymentOption() {
            if ($('[name="payment_gateway_type"]:checked').val() == 2) {
                $('#payment_type').val('1');
                $('.payment_card').show();
                $("#payment").attr("action", siteURL + "payment-gateway");
            } else {
                $('#payment_type').val('3');
                $('.payment_card').hide();
                $("#payment").attr("action", siteURL + "paypal/express-checkout");
            }
        }

        $(document).on('change', '[name="payment_gateway_type"]', function () {
            showPaymentOption();
        });
        showPaymentOption();
    </script>
@stop
