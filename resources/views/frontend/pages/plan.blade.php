@extends('frontend.layouts.membership')

@section('contentHeader')

@stop

@section('content')
    <div class="ss-container">
        <h2 class="membership__title-sm">Signup to shop UK Stores & Ship Internationally</h2>
        @include('frontend.partials.privacy_cookie')

        <ul class="plan ul-reset">
            <li>
                <div class="plan__header">
                    <div class="plan__title">
                        <img src="{!! asset('img/box-icon.png') !!}" alt="Free Membership"/>FREE MEMBERSHIP
                    </div>
                    <div class="plan__price">
                        <h2>£0</h2>
                        <p>/ Free Forever</p>
                    </div>
                </div>
                <div class="plan__content">
                    <ul class="ul-reset plan__point">
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Your free UK Shipping address
                            @endslot
                            @slot('tooltip')
                              Signup to get a free UK warehouse address which can be used as
                              your shipping address to shop UK stores.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Free Package Storage of up to 14 days
                            @endslot
                            @slot('tooltip')
                              Purchases are stored in our facility for up to 14 days,
                              allowing time for multiple package delivery and maximum consolidation savings
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Save up to 30% on shipping
                            @endslot
                            @slot('tooltip')
                              You can receive discounted shipping prices of up to 30% on the
                              standard GPF savings
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                                Free package consolidation
                            @endslot
                            @slot('tooltip')
                              GPF packaging pro’s will combine multiple packages into one for
                              maximum shipping costs and savings.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Personal Shopper Service
                            @endslot
                            @slot('tooltip')
                              Having difficulties buying from a UK store or auction site due to payment
                              methods, verifications or country restrictions? We can make the purchase for you.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Real-time shipment tracking
                            @endslot
                            @slot('tooltip')
                              You’ll receive carrier tracking information as soon as your package
                              leaves our facility.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Flexible shipping options
                            @endslot
                            @slot('tooltip')
                              Choose a specific carrier of your choice, or allow GPF to select the least
                              expensive or best shipping option.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Online account management
                            @endslot
                            @slot('tooltip')
                              View and ship packages in your warehouse, access detailed
                              merchandise information and more 24/7
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Shipment processing time of up to 72hrs
                            @endslot
                            @slot('tooltip')
                              Guaranteed shipment processing within 72 hours
                              (business days) after payment
                            @endslot
                        @endcomponent
                    </ul>
                </div>
                @if(!auth()->check())
                    {!! Form::open(['route'=>'registration.store']) !!}
                    <div class="plan__action">
                        {!! Form::hidden('type','free') !!}
                        <button class="button button--accent">Sign Up</button>
                    </div>
                    {!! Form::close() !!}
                @elseif(auth()->user()->plan_type=='paid')
                    {!!Form::open(['route'=>['users.downgrade']])!!}
                    <div class="plan__action">
                        <button class="button button--accent">Downgrade</button>
                    </div>
                    {!!Form::close()!!}
                @else
                    <div class="plan__action">
                        <button class="button button--accent">Current</button>
                    </div>
                @endif
            </li>
            <li class="active">
                <div class="plan__header">
                    <div class="plan__title">
                        <img src="{!! asset('img/box-icon-yellow.png') !!}" alt="Premium Membership"/>Premium MEMBERSHIP
                    </div>
                    <div class="plan__price">
                        <h2>£{!! getMembershipAmount() !!}</h2>
                        <p>/ month</p>
                    </div>
                </div>
                <div class="plan__content">
                    <ul class="ul-reset plan__point">
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Your free UK shipping address
                            @endslot
                            @slot('tooltip')
                              Signup to get a free UK warehouse address which can be used as
                              your shipping address to shop UK stores
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Free Package Storage of up to 30 days
                            @endslot
                            @slot('tooltip')
                              Purchases are stored in our facility for up to 30 days,
                              allowing time for multiple package delivery and maximum consolidation savings
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Save up to 70% on shipping
                            @endslot
                            @slot('tooltip')
                              You can receive discounted shipping prices of up to 70% on premium
                              GPF savings
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Free package consolidation
                            @endslot
                            @slot('tooltip')
                              GPF packaging pro’s will combine multiple packages into one for
                              maximum shipping costs and savings
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Free package repacking
                            @endslot
                            @slot('tooltip')
                              GPF packaging experts repack every box to ensure your purchases are
                              protected and packages in the most cost-effective way
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Free removal of shoe boxes
                            @endslot
                            @slot('tooltip')
                              Don’t pay for wasted space. GPF packaging experts will remove shoe
                              boxes that consume unnecessary space to reduce your shipping costs
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Up to 2 Free photos of each Item
                            @endslot
                            @slot('tooltip')
                              Request up to two photos of items in your packages. This allows
                              you to confirm delivered purchased in your warehouse before shipping out
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Personal Shopper Service
                            @endslot
                            @slot('tooltip')
                              Having difficulties buying from a UK store or auction site due to payment
                              methods, verifications or country restrictions? We can make the purchase for you
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Real-time Shipment tracking
                            @endslot
                            @slot('tooltip')
                              You’ll receive carrier tracking information as soon as your package
                              leaves our facility.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Flexible shipping options
                            @endslot
                            @slot('tooltip')
                              Choose a specific carrier of your choice, or allow GPF to select the least
                              expensive or best shipping option.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Multiple package shipments
                            @endslot
                            @slot('tooltip')
                              Ship more than one parcel at a time, using one or multiple couriers.
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Online account management
                            @endslot
                            @slot('tooltip')
                              View and ship packages in your warehouse, access detailed
                              merchandise information and more 24/7
                            @endslot
                        @endcomponent
                        @component('frontend.partials.membership.plan_listing')
                            @slot('title')
                              Shipment processing time of up to 24hours
                            @endslot
                            @slot('tooltip')
                              Guaranteed shipment processing within 24 hours
                              (business days) after payment
                            @endslot
                        @endcomponent
                    </ul>
                </div>
                @if(!auth()->check())
                    {!!Form::open(['route'=>'registration.store'])!!}
                    <div class="plan__action">
                        {!! Form::hidden('type','paid') !!}
                        <button class="button button--accent">Sign Up</button>
                    </div>
                    {!!Form::close()!!}
                @elseif(auth()->user()->plan_type=='free')
                    {!!Form::open(['route'=>['users.payment',auth()->user()->id]])!!}
                    {!! Form::hidden('plan_type','paid') !!}
                    <div class="plan__action">
                        <button class="button button--accent">Upgrade</button>
                    </div>
                    {!!Form::close()!!}
                @else
                    <div class="plan__action">
                        <button class="button button--accent">Current</button>
                    </div>
                @endif
            </li>
        </ul>
    </div>
@stop

@section('contentFooter')

@stop
