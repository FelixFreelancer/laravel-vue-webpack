
<header class="header">
    <div class="ss-container">
        <div class="h-wrap">
            <a class="nav-toggle" href="javascript:;" data-toggle=".header__links" data-autoclose>
                <i class="material-icons">menu</i>
            </a>
            <a class="logo" href="{!! url('/') !!}" title="Global Parcel Forward">
                <img src="{!! asset('img/logo.png') !!}" alt="Global Parcel Forward"/>
            </a>
            <div class="support"><a href="https://globalparcelforward.freshdesk.com" target="_blank" title="Helpdesk"> <img src="{!! asset('img/support.png') !!}" alt="Helpdesk"/></a></div>
            <div class="header__links">
                <ul class="nav ul-reset">
                    <li><a href="{!! url('/') !!}" title="Global Parcel Forward">Home</a></li>
                    <li><a href="{!! url()->route('registration.plan') !!}" title="Pricing & Membership">Pricing & Membership</a></li>
                    
                    
                    <li class="dropdown">
   <a href="{!! url('services') !!}" title="Our Services" class="dropdown-toggle" data-toggle="dropdown">Services <span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <li><a href="https://www.globalparcelforward.com/services" title="Our Services">Service Overview</a></li>
        <li class="divider"></li>
        <li><a href="https://www.globalparcelforward.com/parcel-forwarding-service-europe" title="Europe Parcel Forwarding Service">EU Forwarding</a></li>
       <li class="divider"></li>
       <li><a href="https://www.globalparcelforward.com/parcel-forwarding-service-us" title="US Parcel Forwarding Service">US Forwarding</a></li>
       <li class="divider"></li>
       <li><a href="https://www.globalparcelforward.com/parcel-forwarding-service" title="Parcel Forwarding Service">Forward Parcels</a></li>
       <li class="divider"></li>
       <li><a href="https://www.globalparcelforward.com/parcel-forwarding-service-to-australia" title="Australia Parcel Forwarding Service">Australia Service</a></li>
       <li class="divider"></li>
       <li><a href="https://www.globalparcelforward.com/package-forwarding-service" title="Package Forwarding Service">Forward Packages</a></li>
       <li class="divider"></li>
       <li><a href="https://www.globalparcelforward.com/parcel-delivery-service-uk" title="UK Parcel Delivery Service">UK Delivery Service</a></li>
    </ul>
</li>
                  <!--  <li><a href="{!! url('services') !!}" title="Package Forwarding Service">Services</a></li> -->
                  
                    <li><a href="{!!url()->route('home.pricing') !!}" title="Quick Estimate">Estimate</a></li>
                    <li><a href="{!! url('contact-us') !!}" title="Contact Global Parcel Forward">Contact Us</a></li>
                </ul>
                <ul class="ul-reset login">
                    @if(auth()->check())
                    <li data-toggle=".notifications_web" data-autoclose="">
                         <a class="notifications-parent" href="javascript:;">
                             <i class="fas fa-bell"></i>
                             @if($unread_notification_count!=0)
                                 <div class="notification__count">
                                     @if($unread_notification_count<=10)
                                         {!! $unread_notification_count !!}
                                     @else
                                         10+
                                     @endif
                                 </div>
                             @endif
                             <span>Notifications</span>
                         </a>
                         <ul class="ul-reset notifications_web auto__close">
                             <li>
                                 <div class="notifications__new">
                                     <div class="new">
                                         <span class="jq__notification_count">{!! $unread_notification_count !!}</span>
                                         <span>New Notifications</span>
                                     </div>
                                     <a class="notifications__close" href="javascript:;">
                                         <i class="fas fa-times-circle fa-2x"></i>
                                     </a>
                                 </div>
                             </li>
                             @foreach($notifications as $notification)
                                 <li @if($notification['read_at'] !=null) class="read" @endif id="{!! $notification['id'] !!}">
									 <a href="javascript:;" class="jq__read_notification" data-url="{!!   url($notification['redirect_url']) !!}" data-id="{!!  $notification['id'] !!}">
                                         <span class="notifications__title">
                                             {!! $notification['notification'] !!}
                                         </span>
                                         <span class="notifications__time">{!! $notification['time_elapsed'] !!}</span>
                                     </a>
                                 </li>
                             @endforeach
                         </ul>
                     </li>
                        <li data-toggle=".subnav" data-autoclose="">
                            <a href="javascript:;">
                                <i class="fas fa-user" style="color:#33ab43;"></i>
                                <span>{!! ucwords(strtolower(auth()->user()->first_name.' '.auth()->user()->last_name)) !!}</span>
                            </a>
                            <ul class="ul-reset subnav auto__close">
                                <li><a href="{!! url()->route('users.profile.index') !!}">Dashboard</a></li>
                                @if(auth()->user()->role_id==1)
                                    <li><a href="{!! url()->route('admin.home') !!}">Admin</a></li>
                                @endif
                                <li><a href="{!! url()->route('logout') !!}">Log out</a></li>
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{!! url('signin') !!}" title="Sign-in to your GPF Account">
                                <i class="fas fa-sign-in-alt" style="color:#33ab43;"></i><span>Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="{!! url()->route('registration.plan') !!}" title="Signup">
                                <i class="fas fa-user-plus" style="color:#33ab43;"></i><span>Signup</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </div>

</header>
