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
        {!!Form::open(['url'=>'payment/epdq', 'id'=>'payment',  'method' => 'get'])!!}
        <div class="payment-card">
            <div class="payment__card card-shadow">
                <div class="payment__content">
                    <div class="row">
                        <div class="col-md-12">
                            <label>
                                <input type="radio" name="type" value="2" checked>
                                Pay via credit card / debit card
                            </label>
                            <label><input type="radio" name="type" value="1"> Pay via Paypal</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="membership__action">
            <input type="hidden" name="user_id" value="{!! $user !!}">
            <input type="hidden" name="payment_type" value="{!! $payment_type !!}">
            <input type="hidden" name="id" value="{!! $id !!}">
            <button type="submit" class="button button--accent">Pay Now</button>
        </div>
        {!!Form::close()!!}
    </div>
@stop

@section('contentFooter')
    <script>
        function showPaymentOption() {
            if ($('[name="type"]:checked').val() == 2) {
                 $("#payment").attr("action", siteURL+"payment/epdq");
            } else {
                $("#payment").attr("action", siteURL+"payment/paypal");
            }
        }

        $(document).on('change', '[name="type"]', function () {
            showPaymentOption();
        });
        showPaymentOption();
    </script>
@stop
