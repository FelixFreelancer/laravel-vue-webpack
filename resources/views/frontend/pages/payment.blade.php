<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<?php $payment = getPaymentDetail(); ?>
		<form action="{!! $payment['paypal']['url'] !!}" method="post" target="_top" id="payment">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="{!! $payment['paypal']['button'] !!}">
			<input type="hidden" name="custom" value="{!! $user['id'] !!}">
			<input type="hidden" name="notify_url" value="{!! url('/payment/paypal-notify') !!}"/>
		</form>
		<script>
			document.getElementById("payment").submit();
	</script>
	</body>
</html>


<!--<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<?php $payment = getPaymentDetail(); ?>
		
	
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" id="payment">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="{!! $payment['paypal']['button'] !!}">
			<input type="hidden" name="custom" value="{!! $user['id'] !!}">
			<input type="hidden" name="notify_url" value="{!! url('/payment/paypal-notify') !!}"/>{{csrf_field()}}
		</form>
		<script>
			document.getElementById("payment").submit();
	</script>
	</body>
</html>-->
