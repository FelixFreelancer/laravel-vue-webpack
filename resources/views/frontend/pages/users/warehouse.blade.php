@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <div class="dashboard__content dashboard__content--pd card-shadow">
        <h3 class="dashboard__content__title">My Warehouse</h3>
        @if(count($shipments) == 0)
        <div class="to-do">
            <p class="to-do__desc">Hi {!! ucwords(strtolower(auth()->user()->first_name)) !!}, You have no parcels in your warehouse at the moment, start shopping to fill
up this space with goodies!
            </p>
        </div>
        @endif
        @foreach($shipments as $key=>$value)
            <div class="rect"></div>
            <div class="shipments-wrap">
                <div class="shipments-box shipping--box warehouse-box">
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
                                    <label>Weight: </label><span class="normal">{!! $value->parcel_weight !!}kg</span>
                                </li>
                            </ul>
                        </div>
                        <div class="shipments-info shipping--middle">
                            <ul class="ul-reset shipments__info">
                                <li>
                                    <label>Receipts</label>
                                    <a class="pdf-button" href="#nogo">
                                        <img src="{!! asset('img/pdf-icon.jpg') !!}"/><span>P1234567-1.pdf    </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="shipments-info shipping--last">
                            <ul class="ul-reset shipments__info">
                                <li>
                                    <label>Ship with</label>
                                    <img src="{!! $value->shipping_out_logo !!}"/>
                                    <span class="normal">{!! $value->shipping_out_company !!}</span>
                                </li>
                                <li>
                                    <label>Shipping cost:</label><span>£{!! $value->shipping_out_amount !!}(not paid yet)</span>
                                </li>
                                <li>
                                    @if($value->status==3)
                                        <button class="button button--accent">Paid</button>
                                    @elseif($value->status==2)
                                        {!! Form::open(['route'=>['shipments.payment',$value->id]]) !!}
                                        <button class="button button--accent" type="submit">Pay shipping cost</button>
                                        {!! Form::close() !!}
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="parcel__table res-table parcel-desktop">
                        <table>
                            <tr>
                                <th>Items Name</th>
                                <th style="width:40px;">Qty</th>
                                <th style="width:60px;">Amount</th>
                                <th style="width:200px;">Description</th>
                                <th>Tracking Number</th>
                                <th>Dimensions</th>
                            </tr>
                            @foreach($value->items as $item)
                                <tr>
                                    <td>
                                        <div class="item-name"><span>{!! $item->item_name !!}</span></div>
                                    </td>
                                    <td>{!! $item->qty !!}</td>
                                    <td>£{!! $item->amount !!}</td>
                                    <td>
                                        <div class="pt-desc-edit">
                                            <p>
                                                {!! $item->desc !!}
                                            </p>
                                            <button class="button button--edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>{!! $item->tracking_number !!}</td>
                                    <td>
                                        <div class="labelspan">
                                            <label>Length:</label><span>{!! $item->dimension_length !!} cm</span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Width:</label><span>{!! $item->dimension_width !!} cm</span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Height:</label><span>{!! $item->dimension_height !!} cm</span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Weight:</label><span>{!! $item->weight !!} kg</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="parcel__table res-table parcel-mobile">
                        <div class="pm">
                            <h3 class="pm__title">Your Items</h3>
                            @foreach($value->items as $item)
                                <div class="pm__item">
                                    <div class="pm__head">
                                        <div class="pm__name"><span>{!! $item->name !!}</span>
                                            <div class="pm__amt">
                                                <div class="labelspan">
                                                    <label>Qty:</label><span>{!! $item->qty !!}</span>
                                                </div>
                                                <div class="labelspan">
                                                    <label>Amount:</label><span>£{!! $item->amount !!}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-desc-edit pm__desc">
                                        <p>{!! $item->desc !!}</p>
                                        <button class="button button--edit"><i class="fas fa-pencil-alt"></i></button>
                                    </div>
                                    <div class="pm__details">
                                        <div class="labelspan">
                                            <label>Tracking Number:</label><span>{!! $item->tracking_number !!}</span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Dimensions:</label><span>L:{!! $item->dimension_length !!}
                                                cm W:{!! $item->dimension_width !!}cm H:15cm </span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Weight:</label><span>{!! $item->weight !!}kg </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard__footer all-center">
                <div class="checkbox ship__text">
                    <input type="checkbox" id="ship-check-{!! $key !!}" data-id="{!! $key !!}" class="shipCheck" value="1"/>
                    <label for="ship-check-{!! $key !!}">All informations above are correct and packages ready to ship</label>
                </div>
                @if($value->status==2)
                      <button class="button--accent button--ship shipment-{!! $key !!}" onclick="alert('Please pay the shipping cost');">Ship Now</button>
                @elseif($value->status==3)
                {!!Form::open(['route'=>['users.shipment.warehouse-option',$value->id]])!!}
                  <button class="button--accent button--ship">Ship Now</button>
                  {!! Form::close() !!}
                @endif
            </div>
        @endforeach
    </div>
@stop

@section('contentFooter')
<script type="text/javascript">
  $(".button--ship").attr('disabled',true);
  $(document).on('change', '.shipCheck', function () {

    var id = $(this).data('id');
    console.log(id);
      if ($('#ship-check-'+id+':checked').val() == 1) {
          $('.shipment-'+id).removeAttr('disabled');
      } else {
          $('.shipment-'+id).attr('disabled', true);
      }
  });

</script>
@stop
