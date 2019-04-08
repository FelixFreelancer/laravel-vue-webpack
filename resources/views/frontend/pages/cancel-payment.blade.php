@extends('frontend.layouts.membership')

@section('contentHeader')

@stop

@section('content')
    <div class="ss-container is-fluid">
		<p>
        	<h2>Unfortunately, your payment was not completed successfully</h2>
		</p>
		<p>Either the transaction was cancelled by user or a service-side error terminated the transaction.Please <a href="{!! url('/contact-us') !!}" style="color: #007bff;" title="Contact Global Parcel Forward">contact us</a> if you have any questions.</p>
    </div>
@stop

@section('contentFooter')
@stop
