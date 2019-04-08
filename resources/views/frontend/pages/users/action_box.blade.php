@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <div class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <div class="parcel-wrap">
            @if(count($shipments) == 0)
            <div class="to-do">
                <div class="to-do__title"><img src="{!! asset('img/your-to-do.png') !!}"/><span>Your to do list</span>
                </div>
                <p class="to-do__desc">Hi {!! ucwords(strtolower(auth()->user()->first_name)) !!}, You're all good here. No tasks on your to do list!
                </p>
            </div>
            @endif
            <p class="to-do__desc error" id="action_box_messages" style="display: none;">
            </p>
            {!!Form::hidden('collection','GBR')!!}
            {!!Form::hidden('delivery',$country->short_code)!!}
            @foreach($shipments as $key=>$value)
                {!!Form::hidden('shipment[]',$value->id)!!}
                <div class="ps-wrap">
                    <div class="parcel">
                        <div class="parcel_detail">
                            <div class="parcel__slider">
                                @if($value->medias->count()>0)
                                    @foreach($value->medias as $media)
                                        <div class="parcel__item">
                                            <img src="{!! asset($media->media_path.$media->media_name) !!}"/>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="parcel__item">
                                        <img src="{!! asset('img/temp-parcel-2.jpg') !!}"/>
                                    </div>
                                @endif
                            </div>
                            <div class="parcel__received">
                                <div class="parcel__column">
                                    <div class="labelspan">
                                        <label class="bold">Received:</label>
                                        <span>{!! date('d-m-Y | h:i a',strtotime($value->received_on ))!!}</span>
                                    </div>
                                    <div class="labelspan">
                                        <label class="bold">Parcel
                                            Number:</label><span>{!! $value->parcel_number !!}</span>
                                    </div>
                                    <div class="parcel__from">
                                        <label>{!! ucwords(strtolower($value->name)) !!}</label>
                                        <p>
                                            {!! $value->parcel_desc !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="parcel__column">
                                    <div class="dimensions">
                                        <h3 class="dimensions__title">Dimensions</h3>
                                        <div class="labelspan">
                                            <label>Length:</label><span>{!! $value->dimension_length !!}</span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Width:</label><span>{!! $value->dimension_width !!}</span>
                                        </div>
                                        <div class="labelspan">
                                            <label>Height:</label><span>{!! $value->dimension_height !!}</span>
                                        </div>
                                    </div>
                                    <div class="weight">
                                        <div class="labelspan">
                                            <label>Weight:</label><span>{!! $value->parcel_weight !!}</span>
                                        </div>
                                    </div>
                                    <div class="postal-company">
                                        <label>Postal Company</label>
                                        <span>{!! $value->postal_company !!}<a
                                                    href="javascript:;">(#{!! $value->shipment_tracking !!})</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="parcel__table res-table">
                            <table>
                                <tr>
                                    <th style="width:130px;">Items Name</th>
                                    <th style="width:40px;">Qty</th>
                                    <th style="width:60px;">Amount</th>
                                    <th style="width:200px;">Description</th>
                                    <th style="width:110px;">Tracking Number</th>
                                    <th style="width:120px;">Dimensions</th>
                                </tr>
                                @foreach($value->items as $item)
                                    <tr>
                                        @for($i=1;$i<=$item->qty;$i++)
                                            {!!Form::hidden('value['.$value->id.'][]',$item->amount)!!}
                                            {!!Form::hidden('height['.$value->id.'][]',$item->dimension_height)!!}
                                            {!!Form::hidden('width['.$value->id.'][]',$item->dimension_width)!!}
                                            {!!Form::hidden('length['.$value->id.'][]',$item->dimension_length)!!}
                                            {!!Form::hidden('weight['.$value->id.'][]',$item->weight)!!}
                                        @endfor
                                        <td>
                                            <div class="item-name"><span>{!! $item->item_name !!}</span>
                                                @if(isset($item->medias) && $item->medias->count()>0 && auth()->user()->plan_type=='paid')
                                                    <button class="button button--accent button--table itemImageShow"
                                                            data-shipment-id="{!! $value->id !!}"
                                                            data-id="{!! $item->id !!}">
                                                        Photos
                                                    </button>
                                                @else
                                                    <button class="button button--accent button--table RequestItemPhoto"
                                                            data-shipment-id="{!! $value->id !!}"
                                                            data-id="{!! $item->id !!}">
                                                        Request Photo
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{!! $item->qty !!}</td>
                                        <td>{!! $item->amount !!}</td>
                                        <td>
                                            <div class="pt-desc-edit">
                                                <p>{!! $item->desc !!}</p>
                                                <button class="button button--edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{!! $item->tracking_number !!}</td>
                                        <td>
                                            <div class="labelspan">
                                                <label>Length:</label><span>{!! $item->dimension_length !!}</span>
                                            </div>
                                            <div class="labelspan">
                                                <label>Width:</label><span>{!! $item->dimension_width !!}</span>
                                            </div>
                                            <div class="labelspan">
                                                <label>Height:</label><span>{!! $item->dimension_height !!}</span>
                                            </div>
                                            <div class="labelspan">
                                                <label>Weight:</label><span>{!! $item->weight !!}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="parcel-sm">
                            <h2 class="parcel-title">
                                Your Items
                            </h2>
                            <ul class="ul-reset parcel-sm__info">
                                <li>
                                    <div class="parcel-item">
                                        <label class="parcel-item__label">AntFlower Iphone X Case</label>
                                        <button class="button button--accent button--table RequestItemPhoto" data-shipment-id="2" data-id="2">
                                            Request Photo
                                        </button>
                                    </div>
                                    <div class="parcel-item">
                                        <label class="parcel__label">Qty: <span>10</span></label>
                                        <label class="parcel__label">Amount: <span>$500</span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="parcel-text">
                                        <span>It is a long established fact that a reader will be distracted.</span>
                                        <button class="button button--edit">
                                            <svg class="svg-inline--fa fa-pencil-alt fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="pencil-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path></svg>
                                        </button>
                                    </div>
                                </li>
                                <li>
                                    <div class="parcel-tracking">
                                        <label>Tracking number: <span>PLF7876TRD</span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="parcel-dimension">
                                        <label>Dimensions:</label>
                                        <ul class="dimension-sm ul-reset">
                                            <li><label>L: <span>30 cm</span></label></li>
                                            <li><label>W: <span>20 cm</span></label></li>
                                            <li><label>H: <span>15 cm</span></label></li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <label class="parcel-weight">Weight: <span>1kg</span></label>
                                </li>
                            </ul>
                            <ul class="ul-reset parcel-sm__info">
                                <li>
                                    <div class="parcel-item">
                                        <label class="parcel-item__label">AntFlower Iphone X Case</label>
                                        <button class="button button--accent button--table RequestItemPhoto" data-shipment-id="2" data-id="2">
                                            Request Photo
                                        </button>
                                    </div>
                                    <div class="parcel-item">
                                        <label class="parcel__label">Qty: <span>10</span></label>
                                        <label class="parcel__label">Amount: <span>$500</span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="parcel-text">
                                        <span>It is a long established fact that a reader will be distracted.</span>
                                        <button class="button button--edit">
                                            <svg class="svg-inline--fa fa-pencil-alt fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="pencil-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path></svg>
                                        </button>
                                    </div>
                                </li>
                                <li>
                                    <div class="parcel-tracking">
                                        <label>Tracking number: <span>PLF7876TRD</span></label>
                                    </div>
                                </li>
                                <li>
                                    <div class="parcel-dimension">
                                        <label>Dimensions:</label>
                                        <ul class="dimension-sm ul-reset">
                                            <li><label>L: <span>30 cm</span></label></li>
                                            <li><label>W: <span>20 cm</span></label></li>
                                            <li><label>H: <span>15 cm</span></label></li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <label class="parcel-weight">Weight: <span>1kg</span></label>
                                </li>
                            </ul>
                        </div>
                        <div class="shipping-options res-table">
                            <h3 class="shipping-options__title">Shipping Options</h3>
                            <table class="shipping__options" data-shipment-id="{!!$value->id!!}">
                            </table>
                        </div>
                    </div>
                    <div class="send-to-w">
                        {!!Form::open(['route'=>['users.shipment.shipping-option',$value->id],'class'=>'shipping_option_form','data-id'=>$value->id])!!}
                        <div class="send-input">
                            <input type="hidden" name="company_name" id="shipment_company_name_{!!$value->id!!}">
                            <input type="hidden" name="service_name" id="service_name_{!!$value->id!!}">
                            <input type="hidden" name="company_logo" id="shipment_company_logo_{!!$value->id!!}">
                            <input type="hidden" name="shipment_price" id="shipment_price_{!!$value->id!!}">
                            <input type="checkbox" name="accepted_product_info" class="accept_product_info"
                                   data-shipment-id="{!! $value->id !!}" value="1" id="warehouse_{!! $value->id !!}"/>
                            <label for="warehouse_{!! $value->id !!}">
                                I accept all information about the package, price, dimension, etc are correct and
                                acceptable.
                            </label>
                        </div>
                        <button type="submit" class="button button--accent send_warehouse_{!! $value->id !!}" disabled>
                            Send to warehouse
                        </button>
                        {!!Form::close()!!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('frontend.popups.upgrade_account')
    @include('frontend.popups.item_photos')
@stop

@section('contentFooter')
    <script>
        $(document).on('click', '.RequestItemPhoto', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                dataType: 'json',
                method: 'post',
                url: ajaxURL + 'users/shipments/' + $(this).data('shipment-id') + '/shipment-items/' + $(this).data('id') + '/request-photo',
                success: function (data) {
                    if (data['status']) {
                        $('#action_box_messages').html('Request for image is sent.');
                        $('#action_box_messages').show();
                        $this.hide();
                    } else {
                        $('#upgradeAccount').modal('show');
                    }
                }
            });
        });
        $(document).on('click', '.itemImageShow', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                dataType: 'json',
                method: 'post',
                url: ajaxURL + 'users/shipments/' + $(this).data('shipment-id') + '/shipment-items/' + $(this).data('id') + '/photos',
                success: function (data) {
                    if (data['status']) {
                        $('#itemPhotosSlider').html('');
                        for (var index in data['medias']) {
                            $('#itemPhotosSlider').append(
                                '<div class="">' +
                                '   <img src="' + data['medias'][index]['media_name'] + '" width="100"/>' +
                                '</div>'
                            );
                            $('#itemPhotosSliderMain').append(
                                '<div class="">' +
                                '   <img src="' + data['medias'][index]['media_name'] + '" width="100"/>' +
                                '</div>'
                            );
                        }
                        $('#itemPhotos').modal('show');
                    }
                }
            });
        });

        function shippingOptionHtml(data, shipment_id) {
            var str = '';
            for (var index in data) {
                var option = data[index];
                str += '' +
                    '<tr>' +
                    '    <td><img src="' + option['logo'] + '"/>' +
                    '        <div class="namerate"><span>' + option['name'] + '</span>' +
                    '            <ul class="ul-reset rating">' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '            </ul>' +
                    '        </div>' +
                    '    </td>' +
                    '    <td>' +
                    '        <div class="so-price">' +
                    '            <p>£' + option['price'] + '</p><span>(' + moment(option['estimated_delivery_date']).format('MM/DD/YYYY') + ')</span>' +
                    '        </div>' +
                    '    </td>' +
                    '    <td>' +
                    '        ' + option['features'].replace(/<li>/g, '<div class="labelspan">').replace(/<\/li>/g, '</div>') + '' +
                    // '        <div class="labelspan">' +
                    // '            <label>Tracking:</label><span>Yes</span>' +
                    // '        </div>' +
                    // '        <div class="labelspan">' +
                    // '            <label>Insurance:</label><span>No</span>' +
                    // '        </div>' +
                    // '        <div class="labelspan">' +
                    // '            <label>Multi-piece:</label><span>Yes</span>' +
                    // '        </div>' +
                    '    </td>' +
                    '    <td>' +
                    '        <button data-service-name="' + option['service'] + '" data-logo="' + option['logo'] + '" data-name="' + option['name'] + '" data-price="' + option['price'] + '" data-shipment-id="' + shipment_id + '" class="button button--accent button--table selectShippingOption">Choose</button>' +
                    '    </td>' +
                    '</tr>';
            }
            return str;
        }

        function loadShippingOptions() {
            var collection = $('[name="collection"]').val();
            var delivery = $('[name="delivery"]').val();
            $('[name^="shipment"]').each(function (k, i) {
                var weights = [];
                $('[name^="weight[' + $(i).val() + ']"]').each(function (key, item) {
                    weights.push($(item).val());
                });
                var height = $('[name^="height[' + $(i).val() + ']"]');
                var width = $('[name^="width[' + $(i).val() + ']"]');
                var length = $('[name^="length[' + $(i).val() + ']"]');
                var value = $('[name^="value[' + $(i).val() + ']"]');
                var shipment = [];
                for (var index in weights) {
                    var item = {
                        Weight: weights[index],
                        Height: $(height[index]).val(),
                        Width: $(width[index]).val(),
                        Length: $(length[index]).val(),
                        Value: $(value[index]).val(),
                    };
                    shipment.push(item);
                }
                $.ajax({
                    dataType: 'json',
                    method: 'post',
                    url: siteURL + 'shipping-options',
                    data: {
                        shipment: shipment,
                        Collection: $('[name="collection"]').val(),
                        Delivery: $('[name="delivery"]').val()
                    },
                    success: function (data) {
                        $('.shipping__options[data-shipment-id="' + $(i).val() + '"]').html(shippingOptionHtml(data, $(i).val()));
                    }
                });
            });
        }

        $(function () {
            loadShippingOptions();
        });
        $(document).on('click', '.selectShippingOption', function (e) {
            e.preventDefault();
            var logo = $(this).data('logo');
            var name = $(this).data('name');
            var service = $(this).data('service-name');
            var price = $(this).data('price');
            var shipment_id = $(this).data('shipment-id');

            $('#shipment_company_logo_' + shipment_id).val(logo);
            $('#shipment_company_name_' + shipment_id).val(name);
            $('#service_name_' + shipment_id).val(service);
            $('#shipment_price_' + shipment_id).val(price);
        });
        $(document).on('change', '.accept_product_info', function (e) {
            e.preventDefault();
            if ($('.accept_product_info[data-shipment-id="' + $(this).data('shipment-id') + '"]:checked').val() == 1) {
                $('.send_warehouse_' + $(this).data('shipment-id')).removeAttr('disabled');
            } else {
                $('.send_warehouse_' + $(this).data('shipment-id')).attr('disabled', true);
            }
        });

        $(document).on('submit', '.shipping_option_form', function (e) {
            if ($('#shipment_company_name_' + $(this).data('id')).val() == '' ||
                $('#shipment_company_logo_' + $(this).data('id')).val() == '' ||
                $('#shipment_price_' + $(this).data('id')).val() == '' ||
                $('#warehouse_' + $(this).data('id')).val() == '' ||
                $('#service_name_' + $(this).data('id')).val() == '') {
                alert('Please select one of the shipping option.');
                e.preventDefault();
            }
        });
    </script>
@stop
