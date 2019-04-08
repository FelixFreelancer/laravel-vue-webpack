@extends('frontend.layouts.membership')

@section('contentHeader')

@stop

@section('content')
    <div class="ss-container is-fluid">
        <h2 class="membership__title-sm">Payment Details</h2>
        <p class="membership__text">You will be able to add/change billing addresses after you sign up.</p>

        @if(session()->has('message') && session()->has('class'))
            @if(session('class')=='danger')
                <p class="already__desc error">{!! session('message') !!}</p>
            @elseif(session('class')=='success')
                <p class="already__desc success">{!! session('message') !!}</p>
            @endif
        @endif
        {!!Form::open(['route'=>'payment-gateway.index', 'id'=>'payment',  'method' => 'get'])!!}
        <div class="payment-card">
            <div class="payment__card card-shadow">
                <div class="payment__content">
                    <div class="row">
                        <div class="col-md-12">
                            <label>
                                <input type="radio" name="payment_gateway_type" value="2" checked>
                                Pay via credit card / debit card
                            </label>
                            <label><input type="radio" name="payment_gateway_type" value="1"> Pay via Paypal</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="membership__action">
            <input type="hidden" name="user_id" value="{!! $user['id'] !!}">
            <input type="hidden" name="custom" value="{!! $user['id'] !!}">
            <input type="hidden" name="recurring" value="1">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="src" value="0">
            <input type="hidden" name="hosted_button_id" value="TFRCSZA8WW43U">
            <input type="hidden" name="notify_url" value="{!! url('/payment/paypal-notify') !!}"/>
            <button type="submit" class="button button--accent">Pay Now</button>
        </div>
        {!!Form::close()!!}
    </div>
@stop

@section('contentFooter')
    <script>
        function showPaymentOption() {
            if ($('[name="payment_gateway_type"]:checked').val() == 2) {
                 $("#payment").attr("action", siteURL+"payment/epdq");
            } else {
                $("#payment").attr("action", "{!! config('site.payment.paypal.url') !!}");
            }
        }

        $(document).on('change', '[name="payment_gateway_type"]', function () {
            showPaymentOption();
        });
        showPaymentOption();
    </script>
@stop
