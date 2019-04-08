<header class="m-grid__item    m-header " data-minimize-offset="200" data-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-dark ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="{!! url()->route('home') !!}" class="m-brand__logo-wrapper">
                                                        <img alt="" src="{!! asset('img/logo.png') !!}"/>
                            {{--<h2>GPF</h2>--}}
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block
					 ">
                            <span></span>
                        </a>
                        <!-- END -->
                        <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle"
                           class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                        <!-- END -->
                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;"
                           class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>
                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>
            <!-- END: Brand -->
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                <!-- BEGIN: Horizontal Menu -->
                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right 	m-dropdown--mobile-full-width"
                                data-dropdown-toggle="click" data-dropdown-persistent="true">
                                <a href="#" class="m-nav__link m-dropdown__toggle"
                                   @if($unread_notification_count >0)
                                   id="m_topbar_notification_icon"
                                        @endif
                                >
                                    @if($unread_notification_count >0)
                                        <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
                                    @endif
                                    <span class="m-nav__link-icon"><i class="flaticon-music-2"></i></span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center"
                                             style="background: url({!! asset('img/notification_bg.jpg') !!}); background-size: cover;">
														<span class="m-dropdown__header-title">
															{!! $unread_notification_count !!} New
														</span>
                                            <span class="m-dropdown__header-subtitle">
															User Notifications
														</span>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <div class="tab-content">
                                                    <div class="tab-pane active"
                                                         id="topbar_notifications_notifications" role="tabpanel">
                                                        <div class="m-scrollable" data-scrollable="true"
                                                             data-max-height="250" data-mobile-max-height="200">
                                                            <div class="m-list-timeline m-list-timeline--skin-light">
                                                                <div class="m-list-timeline__items">
                                                                    @foreach($notifications as $notification)
                                                                        <div class="m-list-timeline__item @if($notification->read_at!=null) m-list-timeline__item--read @endif">
                                                                            <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                                                            @if($notification->type=='App\Notifications\RequestShipmentItemPhoto')
                                                                                <a href="{!! url()->route('admin.shipment-items.edit',[$notification->data['shipment_id'],$notification->data['id']]) !!}?notification_id={!! $notification->id !!}"
                                                                                   class="m-list-timeline__text">
                                                                                    {!! $notification->data['user_name'] !!}
                                                                                    is requesting photo of item named
                                                                                    as {!! $notification->data['item_name'] !!}
                                                                                    .

                                                                                </a>
                                                                                <span class="m-list-timeline__time">
                                                                                {!! $notification->time_elapsed !!}
                                                                                    </span>
                                                                            @elseif($notification->type=='App\Notifications\QuotationCreated')
                                                                                <a href="{!! url()->route('admin.quotations.show',$notification->data['quotation_number']) !!}?notification_id={!! $notification->id !!}"
                                                                                   class="m-list-timeline__text">
                                                                                    {!! $notification->data['user_name'] !!}
                                                                                    has created new shipment with
                                                                                    {!! $notification->data['quotation_number'] !!}
                                                                                    shipment number.
                                                                                </a>
                                                                                <span class="m-list-timeline__time">
                                                                                    {!! $notification->time_elapsed !!}
                                                                                </span>
                                                                            @elseif($notification->type=='App\Notifications\ShipmentCreated')
                                                                                <a href="{!! url()->route('users.action_box') !!}?notification_id={!! $notification->id !!}"
                                                                                   class="m-list-timeline__text">
                                                                                    New Shipment has been added by Admin.
                                                                                </a>
                                                                                <span class="m-list-timeline__time">
                                                                                    {!! $notification->time_elapsed !!}
                                                                                </span>
                                                                            @elseif($notification->type=='App\Notifications\QuotationReponseFromAdmin')
                                                                                <a href="{!! url()->route('users.shopper.index') !!}?notification_id={!! $notification->id !!}"
                                                                                   class="m-list-timeline__text">
                                                                                    Admin has responded for shipment with
                                                                                    {!! $notification->data['quotation_number'] !!}
                                                                                    shipment number.
                                                                                </a>
                                                                                <span class="m-list-timeline__time">
                                                                                    {!! $notification->time_elapsed !!}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                data-dropdown-toggle="click">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-topbar__userpic">
                                        {{--@if(auth()->user()->profile!=null)--}}
                                        {{--<img src="{!! asset(auth()->user()->profile_path.'/'.auth()->user()->profile) !!}"--}}
                                        {{--class="m--img-rounded m--marginless m--img-centered" alt=""/>--}}
                                        {{--@else--}}
                                        <img src="{!! asset('img/user4.jpg') !!}"
                                             class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                        {{--@endif--}}
                                    </span>
                                    <span class="m-topbar__username m--hide">
                                        {!! ucwords(strtolower(auth()->user()->first_name.' '.auth()->user()->last_name)) !!}
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center"
                                             style="background: url({!! asset('img/user_profile_bg.jpg') !!}); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">
                                                    {{--@if(auth()->user()->profile!=null)--}}
                                                    {{--<img src="{!! asset(auth()->user()->profile_path.'/'.auth()->user()->profile) !!}"--}}
                                                    {{--class="m--img-rounded m--marginless" alt=""/>--}}
                                                    {{--@else--}}
                                                    <img src="{!! asset('img/user4.jpg') !!}"
                                                         class="m--img-rounded m--marginless" alt=""/>
                                                    {{--@endif--}}
                                                </div>
                                                <div class="m-card-user__details">
                                                    <span class="m-card-user__name m--font-weight-500">
                                                        {!! ucwords(strtolower(auth()->user()->first_name.' '.auth()->user()->last_name)) !!}
                                                    </span>
                                                    <a href="" class="m-card-user__email m--font-weight-300 m-link">
                                                        {!! auth()->user()->email !!}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__section m--hide">
                                                        <span class="m-nav__section-text">
                                                            Section
                                                        </span>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="#nogo{{--{!! url()->route('admin.myprofile') !!}--}}"
                                                           class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                            <span class="m-nav__link-title">
                                                                <span class="m-nav__link-wrap">
                                                                    <span class="m-nav__link-text">
                                                                        My Profile
                                                                    </span>
                                                                    {{--<span class="m-nav__link-badge">
                                                                        <span class="m-badge m-badge--success">
                                                                            2
                                                                        </span>
                                                                    </span>--}}
                                                                </span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    {{--<li class="m-nav__item">
                                                        <a href="header/profile.html" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                            <span class="m-nav__link-text">
                                                                            Activity
                                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="header/profile.html" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                            <span class="m-nav__link-text">
                                                                            Messages
                                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="header/profile.html" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                            <span class="m-nav__link-text">
                                                                            FAQ
                                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="header/profile.html" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                            <span class="m-nav__link-text">
                                                                            Support
                                                                        </span>
                                                        </a>
                                                    </li>--}}
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="{!! url()->route('logout') !!}"
                                                           class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                            Logout
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Topbar -->
            </div>
        </div>
    </div>
</header>
