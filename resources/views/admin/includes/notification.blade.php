<div id="alertShowing"></div>
@if(session()->has('message') && session()->has('class'))
	<div class="alert alert-{!! session('class') !!} alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
		{!! session('message') !!}
	</div>
@endif