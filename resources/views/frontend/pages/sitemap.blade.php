@extends('frontend.layouts.common')

@section('contentHeader')

@stop

@section('content')
    <div class="page-cover">
        <h3 class="page-cover__title">Sitemap</h3>
    </div>
    <div class="ss-container">
        <ul class="ul-reset sitemap">
            <li>
                <div class="sitemap__col">
                    <h3 class="sitemap__title card-shadow">Our Company</h3>
                    <ul class="ul-reset sitemap__links">
                        <li><a href="{!! url('') !!}#home_features" title="How It Works">How It Works</a></li>
                        <li><a href="{!! url('') !!}#home" title="Global Parcel Forward">Homepage</a></li>
                     <!--   <li><a href="#nogo">About Us</a></li> -->
                        <li><a href="{!! url('sitemap') !!}" title="Sitemap">Sitemap</a></li>
                        <li><a href="{!! url('blog') !!}" title="Our Blog">Blog</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="sitemap__col">
                    <h3 class="sitemap__title card-shadow">Our Services</h3>
                    <ul class="ul-reset sitemap__links">
                        <li><a href="{!! url('registration/plan') !!}" title="Pricing & Membership">Pricing & Membership</a></li>
                        <li><a href="{!! url('services') !!}" title="Package Forwarding Service">Services</a></li>
						 <li><a href="{!! url('parcel-forwarding-service-europe') !!}" title="Parcel Forwarding Service UK to Europe">EU Forwarding</a></li>
						  <li><a href="{!! url('parcel-forwarding-service-us') !!}" title="Buy in UK Ship to US">US Forwarding</a></li>
						   <li><a href="{!! url('parcel-forwarding-service-us') !!}" title="Parcel Forwarding Service">Forward Parcels</a></li>
						    <li><a href="{!! url('parcel-forwarding-service-to-australia') !!}" title="Australia Parcel Forwarding Service">Australia Service</a></li>
							 <li><a href="{!! url('package-forwarding-service') !!}" title="Package Forwarding Service">Forward Packages</a></li>
							  <li><a href="{!! url('parcel-delivery-service-uk') !!}" title="UK Parcel Delivery Service">UK Delivery Service</a></li>
                    <!--    <li><a href="{!! url('') !!}#home_features">How It Works</a></li> -->
                        <li><a href="{!! url('faqs') !!}" title="International Shipping Questions">FAQ's</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="sitemap__col">
                    <h3 class="sitemap__title card-shadow">Customer</h3>
                    <ul class="ul-reset sitemap__links">
                        <li><a href="{!! url('signin?next=dashboard-profile') !!}">Customer Dashboard</a></li>
                        <li><a href="{!! url('signin') !!}" title="Sign-in to your GPF Account">Login</a></li>  
                        <li><a href="{!! url()->route('registration.plan') !!}" title="Signup">Signup</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="sitemap__col">
                    <h3 class="sitemap__title card-shadow">Support</h3>
                    <ul class="ul-reset sitemap__links">
                        <li><a href="{!! url('contact-us') !!}" title="Contact Global Parcel Forward">Contact Us</a></li>
                        <li><a href="https://tawk.to/globalparcelforward" title="Live Support">Live Support</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
@stop

@section('contentFooter')

@stop
