@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <form class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <h3 class="dashboard__content__title">Shipments History</h3>
        @if(count($shipments) == 0)
        <div class="to-do">
            <p class="to-do__desc">Hi {!! ucwords(strtolower(auth()->user()->first_name)) !!}, You have no items that
are delivered at the moment!
            </p>
        </div>
        @else
        <div class="history-wrap">
            <div class="history-table res-table">
                <table>
                    <tr>
                        <th>Shipment #</th>
                        <th>Status</th>
                        <th>Date Shipped</th>
                        <th>Courier</th>
                        <th>Tracking #</th>
                    </tr>
                    @foreach($shipments as $key => $value)
                    <tr>
                        <td>{!! $value->shipping_in_tracking !!}</td>
                        <td>Delivered</td>
                        <td>{!! date('F d, Y',strtotime($value->delivered_at)) !!}</td>
                        <td>{!! $value->shipping_in_company !!}</td>
                        <td>{!! $value->shipping_out_tracking !!}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

    </form>
@stop

@section('contentFooter')

@stop
