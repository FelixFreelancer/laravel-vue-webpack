<div class="membership__header">
    <div class="ss-container">
        <ul class="membership__nav ul-reset">
            <li @if(url()->current()==url('registration/plan')) class="active" @endif>
                <a href="javascript:;">
                    <span>
                        <img src="{!! asset('img/membership.png') !!}"/>
                    </span>
                    <label>Select Membership Plan</label>
                </a>
            </li>
            <li @if(url()->current()==url('registration/membership')) class="active" @endif>
                <a href="javascript:;">
                    <span>
                        <img src="{!! asset('img/membership-info.png') !!}"/>
                    </span>
                    <label>Member Info &amp; Billing Address</label>
                </a>
            </li>
            <li @if(url()->current()==url('payment')) class="active" @endif>
                <a href="javascript:;">
                    <span>
                        <img src="{!! asset('img/membership-payment.png') !!}"/>
                    </span>
                    <label>Payment Details</label>
                </a>
            </li>
        </ul>
    </div>
</div>