@extends('frontend.layouts.profile')

@section('contentHeader')
<link href="{!! loadUserCssFile() !!}" rel="stylesheet"></head>
@stop

@section('content')
  <div class="wrap">
    <div id="app"></div>
  </div>
@stop

@section('contentFooter')
<script type="text/javascript" src="{!! loadUserJsFile() !!}"></script></body>
@stop
