@extends('frontend.layouts.membership')

@section('contentHeader')

@stop

@section('content')
    <div class="ss-container is-fluid">
        <h2 class="membership__title-sm text-center" style="display: block;">GPF Payment Gatewat</h2>
        {!!Form::open(['route'=>'payment-gateway.store','id'=>'payment_form'])!!}
        <div class="membership__action row">
            {!! Form::hidden('payment_type',$payment_type) !!}
            {!! Form::hidden('user_id',$user_id) !!}
            {!! Form::hidden('status','',['id'=>'payment_form_status']) !!}
            <div class="col-md-6">
                <a class="button button--accent bg-danger payment_form_submit" data-status="2" href="#nogo">Decline</a>
            </div>
            <div class="col-md-6 ">
                <a class="button button--accent payment_form_submit" data-status="1" href="#nogo">Accept</a>
            </div>
        </div>
        {!!Form::close()!!}
    </div>
@stop

@section('contentFooter')
    <script>
        $(document).on('click', '.payment_form_submit', function (e) {
            e.preventDefault();
            $('#payment_form_status').val($(this).data('status'));
            $('#payment_form').submit();
        });
    </script>
@stop
