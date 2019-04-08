@extends('frontend.layouts.master')

@section('contentHeader')

@stop

@section('content')

{!!Form::open(['url'=>$url, 'id'=>'payment',  'method' => 'get'])!!}
<div class="membership__action">
	<input type="hidden" name="custom" value="{!! $user !!}">
	<input type="hidden" name="recurring" value="1">
	<input type="hidden" name="cmd" value="_s-xclick">
	@if($autorenew == 0)
	<input type="hidden" name="a3" value="0">
	@endif
	<input type="hidden" name="src" value="{!! $autorenew !!}">
	<input type="hidden" name="hosted_button_id" value="TFRCSZA8WW43U">
	<input type="hidden" name="notify_url" value="{!! url('/payment/paypal-notify') !!}"/>
</div>
{!!Form::close()!!}
@stop

@section('contentFooter')
<script type="text/javascript">
  $(function() {
    $("#payment").submit();
  });
</script>
@stop
