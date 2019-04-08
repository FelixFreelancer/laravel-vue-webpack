@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')

    <form class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <div class="shipments-wrap">
            <h3 class="dashboard__content__title">Ready for Shipping</h3>
            @if(count($shipments) == 0)
            <div class="to-do">
                <p class="to-do__desc">Hi {!! ucwords(strtolower(auth()->user()->first_name)) !!}, You have no items that
are ready for shipping at the moment!
                </p>
            </div>
            @endif
            @foreach($shipments as $key=>$value)
            <div class="shipments-box shipping--box">
                <div class="shipments__header">
                    <div class="shipments-info shipping--first">
                        <ul class="ul-reset shipments__info">
                            <li>
                                <label class="shipments__title">{!! $value->name !!}</label>
                                <p class="shipments__text">{!! $value->parcel_desc !!}</p>
                                <a href="#nogo">View more... </a>
                            </li>
                            <li>
                                <div class="dimensions dimensions--shipping">
                                    <h3 class="dimensions__title">Dimensions</h3>
                                    <div class="labelspan">
                                        <label>Length:</label><span>{!! $value->dimension_length !!} cm</span>
                                    </div>
                                    <div class="labelspan">
                                        <label>Width:</label><span>{!! $value->dimension_width !!} cm</span>
                                    </div>
                                    <div class="labelspan">
                                        <label>Height:</label><span>{!! $value->dimension_height !!} cm</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="weight">
                                    <div class="labelspan">
                                        <label>Weight:</label><span>{!! $value->parcel_weight !!} kg</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="shipments-info shipping--middle">
                        <ul class="ul-reset shipments__info">
                            <li>
                                <label>Ship with</label>
                                <img src="{!! $value->shipping_out_logo !!}"/>
                                <span class="normal">{!! $value->shipping_out_company !!}</span>
                            </li>
                            <li>
                                <div class="labelspan">
                                    <label>Shipping cost:</label><span>£{!! $value->shipping_out_amount !!}</span>
                                </div>
                                <a class="button paid" href="#nogo">Paid</a>
                            </li>
                        </ul>
                    </div>
                    <div class="shipments-info shipping--last">
                        <ul class="ul-reset shipments__info">
                            <li>
                                <label>Status: </label>
                                <a class="button pending" href="#nogo">
                                  @if($value->status == 3)
                                    PENDING VERIFICATION
                                  @elseif($value->status == 5)
                                    Processing
                                  @endif
                                </a>
                            </li>
                            <!-- <li>
                                <label>Deliver Date</label><span>February 20, 2018 </span>
                            </li>
                            <li>
                                <label>Tracking Number</label><span class="normal">PLF23847238    </span>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </form>
@stop

@section('contentFooter')

@stop
