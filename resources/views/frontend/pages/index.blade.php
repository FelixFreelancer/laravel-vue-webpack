@extends('frontend.layouts.master')

@section('contentHeader')

@stop

@section('content')
    <a href="{!! url('home') !!}" target="_blank">Home</a>
    <a href="{!! url('signin') !!}" target="_blank">Signin</a>
    <a href="{!! url('membership') !!}" target="_blank">Membership</a>
    <a href="{!! url('plan') !!}" target="_blank">Plan</a>
    <a href="{!! url('payment') !!}" target="_blank">Payment</a>
    <a href="{!! url('dashboard-profile') !!}" target="_blank">Dashboard (Profile Summary)</a>
    <a href="{!! url('dashboard-security') !!}" target="_blank">Dashboard (Security)</a>
    <a href="{!! url('blog') !!}" target="_blank">Blog</a>
    <a href="{!! url('blog-detail') !!}" target="_blank">Blog Detail</a>
    <a href="{!! url('faqs') !!}" target="_blank">FAQs</a>
    <a href="{!! url('dashboard-action') !!}" target="_blank">Dashboard (Action box)</a>
    <a href="{!! url('dashboard-history') !!}" target="_blank">Dashboard (History)</a>
    <a href="{!! url('dashboard-subscription') !!}" target="_blank">Dashboard (Subscription)</a>
    <a href="{!! url('dashboard-shipments') !!}" target="_blank">Dashboard (Shipments)</a>
    <a href="{!! url('dashboard-invoice') !!}" target="_blank">Dashboard (Invoice)</a>
    <a href="{!! url('dashboard-shopper') !!}" target="_blank">Dashboard (Shopper)</a>
    <a href="{!! url('dashboard-shipping') !!}" target="_blank">Dashboard (Shipping)</a>
    <a href="{!! url('dashboard-warehouse') !!}" target="_blank">Dashboard (Warehouse) </a>
    <a href="{!! url('services') !!}" target="_blank">Our Services</a>
    <a href="{!! url('legal-terms') !!}" target="_blank">Legal Terms</a>
    <a href="{!! url('contact-us') !!}" target="_blank">Contact us</a>
    <a href="{!! url('sitemap') !!}" target="_blank">Sitemap</a>
@stop

@section('contentFooter')

@stop