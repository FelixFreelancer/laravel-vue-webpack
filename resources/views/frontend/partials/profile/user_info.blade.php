<ul class="ul-reset dcustomer card-shadow">
    <li>
        <ul class="ul-reset user-block">
            <li class="user-info">
                <div class="dcustomer__img" data-toggle="modal" data-target="#profile_pic" >
        			@if(auth()->user()->image_name != '')
        				<img class="profile_pic" src="{!! asset(auth()->user()->image_path.auth()->user()->image_name) !!}"/>
        			@else
        				<img class="profile_pic" src="{!! asset('img/user-demo.png') !!}"/>
        			@endif
        		</div>
                <div class="dcustomer-info">
                    <div class="dcustomer__info">
                        <div class="dcustomer__name">
                            <h3>{!!ucwords(strtolower(auth()->user()->first_name." ".auth()->user()->last_name))!!}</h3>
                        </div>
                        @if(auth()->user()->plan_type=='free')
                            <div class="dcustomer__type">
                                <img src="{!! asset('img/free.png') !!}"/><span>Free Member</span>
                            </div>
                        @elseif(auth()->user()->plan_type=='paid')
                            <div class="dcustomer__type">
                                <img src="{!! asset('img/premium.png') !!}"/><span>Premium Member</span>
                            </div>
                        @endif
                    </div>

                    <div class="dcustomer__number">
                        <div class="dcustomer__info">
                            <label>Customer Number:</label><span>{!!auth()->user()->customer_code!!}</span>
                        </div>
                        <div class="dcustomer__info">
                            <label>Suite Number:</label>
                            <span>{!!App\Models\Country::find(auth()->user()->cd_country)->suite_number!!}</span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </li>
    <li>
        <div class="dcustomer__info">
            <label>
                Your UK warehouse Address:
            </label>
            <span>
                {!!ucwords(strtolower(auth()->user()->first_name." ".auth()->user()->last_name))!!} <br/>
                {!!$address['uk_warehouse_address_line_1']!!} <br/>
                Suite {!!App\Models\Country::find(auth()->user()->cd_country)->suite_number!!}, {!!auth()->user()->customer_code!!} <br/>
                {!!$address['uk_warehouse_address_line_2']!!} <br/>
                {!!$address['uk_warehouse_country']!!} <br/>
            </span>
        </div>
    </li>
</ul>
