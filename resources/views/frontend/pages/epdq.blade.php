@extends('frontend.layouts.master')

@section('contentHeader')

@stop

@section('content')

<form method="post" action="{!! $action !!}" id="epdq">
  @foreach($formParameter as $key => $value)
    <input type="hidden" name="{!! $key !!}" value="{!! $value !!}" />
  @endforeach
</form>
@stop

@section('contentFooter')
<script type="text/javascript">
  $(function() {
    $("#epdq").submit();
  });
</script>
@stop
