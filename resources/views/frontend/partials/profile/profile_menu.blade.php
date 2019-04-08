<div class="dashboard__menus">
    <ul class="ul-reset dmenus">
        <li>
            <a class="dmenus__link @if(url()->current()==url()->route('users.profile.index')) active @endif"
               href="{!! url()->route('users.profile.index') !!}">
                <div class="dmenu__icon">
                    <img src="{!! asset('img/profile-summary.png') !!}"/>
                </div>
                <span>Profile Summary</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i>
                </div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url()->route('users.action_box')) active @endif"
               href="{!! url()->route('users.action_box') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/action-box.png') !!}"/></div>
                <span>Action box</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url()->route('users.warehouse')) active @endif"
               href="{!! url()->route('users.warehouse') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/warehouse.png') !!}"/></div>
                <span>My Warehouse</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url('dashboard-shipping')) active @endif"
               href="{!! url('dashboard-shipping') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/ready-for-shipping.png') !!}"/></div>
                <span>Ready for shipping</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url('dashboard-shipments')) active @endif"
               href="{!! url('dashboard-shipments') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/shipments.png') !!}"/></div>
                <span>Shipments</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url('dashboard-history')) active @endif"
               href="{!! url('dashboard-history') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/shipments-history.png') !!}"/></div>
                <span>Shipments History</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url('personal-shopper')) active @endif"
               href="{!! url('personal-shopper') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/personal-shopper.png') !!}"/></div>
                <span>Personal shopper</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url('dashboard-invoice')) active @endif"
               href="{!! url('dashboard-invoice') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/invoices.png') !!}"/></div>
                <span>invoices</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url()->route('users.subscription')) active @endif"
               href="{!! url()->route('users.subscription') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/subscriptions.png') !!}"/></div>
                <span>Subscriptions Plan</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
        <li>
            <a class="dmenus__link @if(url()->current()==url()->route('users.security.index')) active @endif"
               href="{!! url()->route('users.security.index') !!}">
                <div class="dmenu__icon"><img src="{!! asset('img/security.png') !!}"/></div>
                <span>Security Setting</span>
                <div class="dmenu__arrow"><i class="fas fa-angle-right fa-sm"> </i></div>
            </a>
        </li>
    </ul>
</div>
