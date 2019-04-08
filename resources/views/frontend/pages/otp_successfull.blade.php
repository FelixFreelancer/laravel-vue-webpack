@extends('frontend.layouts.dashboard')

@section('contentHeader')

@stop

@section('content')
    <section class="section text-center" style="margin-top:100px;">
        Thank you for completing the verification process. Your account is now fully active.
        <br>
        <a class="button button--accent" href="{!!url('/dashboard')!!}">Go to Dashboard</a>
    </section>
@stop

@section('contentFooter')
@stop
