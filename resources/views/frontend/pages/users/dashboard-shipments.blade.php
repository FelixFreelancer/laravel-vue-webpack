@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <form class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <div class="shipments-wrap">
            <h3 class="dashboard__content__title">Shipments</h3>
            @if(count($shipments) == 0)
            <div class="to-do">
                <p class="to-do__desc">Hi {!! ucwords(strtolower(auth()->user()->first_name)) !!}, You have no items that
are shipped at the moment!
                </p>
            </div>
            @endif
            @foreach($shipments as $key => $value)
            <div class="shipments-box">
                <div class="shipments__header">
                    <div class="shipments-detail">
                        <ul class="ul-reset shipments__info">
                            <li>
                                <label>Parcel Sent: </label>
                                <Span>{!! date('F d, Y H:i a',strtotime($value->shipping_out_at)) !!}</Span>
                            </li>
                            <li>
                              <label class="shipments__title">{!! $value->name !!}</label>
                              <p class="shipments__text">{!! $value->parcel_desc !!}</p>
                              <a href="#nogo">View more...</a>
                            </li>
                            <li>
                                <label>Dimensions: </label><span class="normal">Length: {!! $value->dimension_length !!} cm | width: {!! $value->dimension_width !!} cm | Height : {!! $value->dimension_height !!} cm</span>
                            </li>
                            <li>
                                <label>Weight: </label><span class="normal">{!! $value->parcel_weight !!} kg</span>
                            </li>
                        </ul>
                    </div>
                    <div class="shipments-info">
                        <ul class="ul-reset shipments__info">
                            <li>
                                <label>Ship with</label>
                                <img src="{!! $value->shipping_out_logo !!}"/>
                                <span class="normal">{!! $value->shipping_out_company !!}</span>
                            </li>
                            <li>
                                <label>Tracking Number</label><span class="normal">{!! $value->shipping_out_tracking !!}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="shipments__content">
                    <h3 class="dashboard__content__title">Invoice Details
                        <div class="invoice__table__wrap">
                            <table class="invoice__table">
                                <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $total = 0;
                                   ?>
                                @foreach($value->items as $k => $val)
                                  <?php
                                    $tot = $val->qty * $val->amount;
                                    $total += $tot;
                                   ?>
                                  <tr>
                                      <td>{!! $val->item_name !!}</td>
                                      <td>{!! $val->qty !!}</td>
                                      <td>{!! $val->amount !!}</td>
                                      <td>£{!! $tot !!}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2">
                                        <label>Total:</label><span>£{!! $total !!}</span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </h3>
                </div>
            </div>
            @endforeach
        </div>
    </form>
@stop

@section('contentFooter')

@stop
