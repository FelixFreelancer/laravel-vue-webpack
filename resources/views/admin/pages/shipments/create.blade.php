@extends('admin.layouts.master')

@section('contentHeader')
    <link rel="stylesheet" href="{!! asset(mix('css/admin/vendor/croppie.css')) !!}">
@stop

@section('page_title')
    @if(isset($shipment))
        Edit Shipment
    @else
        Create Shipment
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <!--begin::Form-->
                @if(isset($shipment))
                    {!!Form::model($shipment,['route'=>['admin.shipments.update',$shipment->id],'method'=>'put','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @else
                    {!!Form::open(['route'=>'admin.shipments.store','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @endif
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('user-id'))has-danger @endif">
                            {!!Form::label('user_id','Users')!!}
                            @if(isset($shipment))
                                {!!Form::select('user_id',$users,old('user_id'),['class'=>'form-control','id'=>'user_id'])!!}
                            @else
                                {!!Form::select('user_id',[],old('user_id'),['class'=>'form-control','placeholder'=>'Select User','id'=>'user_id'])!!}
                            @endif
                            @if($errors->has('user_id'))
                                <p class="help-block">{!!$errors->first('user_id')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('name'))has-danger @endif">
                            {!!Form::label('name','Name')!!}
                            {!!Form::text('name', old('name'), ['class'=>'form-control','placeholder'=>'Name'])!!}
                            @if($errors->has('name'))
                                <p class="form-control-feedback">{!!$errors->first('name')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('parcel_number'))has-danger @endif">
                            {!!Form::label('parcel_number','Parcel Number')!!}
                            {!!Form::text('parcel_number', old('parcel_number'), ['class'=>'form-control','placeholder'=>'Parcel Number'])!!}
                            @if($errors->has('parcel_number'))
                                <p class="form-control-feedback">{!!$errors->first('parcel_number')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('parcel_desc'))has-danger @endif">
                            {!!Form::label('parcel_desc','Parcel Description')!!}
                            {!!Form::textarea('parcel_desc', old('parcel_desc'), ['class'=>'form-control','rows'=>3,'placeholder'=>'Parcel Description'])!!}
                            @if($errors->has('parcel_desc'))
                                <p class="form-control-feedback">{!!$errors->first('parcel_desc')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('received_on'))has-danger @endif">
                            {!!Form::label('received_on','Received On')!!}
                            {!!Form::text('received_on', old('received_on'), ['class'=>'form-control','id'=>'received_on','placeholder'=>'Received On'])!!}
                            @if($errors->has('received_on'))
                                <p class="help-block">{!!$errors->first('received_on')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('parcel_weight'))has-danger @endif">
                            {!!Form::label('parcel_weight','Weight')!!}
                            {!!Form::text('parcel_weight', old('parcel_weight'), ['class'=>'form-control','placeholder'=>'Weight'])!!}
                            @if($errors->has('parcel_weight'))
                                <p class="help-block">{!!$errors->first('parcel_weight')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('dimension_length'))has-danger @endif">
                            {!!Form::label('dimension_length','Length')!!}
                            {!!Form::text('dimension_length', old('dimension_length'), ['class'=>'form-control','placeholder'=>'Length'])!!}
                            @if($errors->has('dimension_length'))
                                <p class="help-block">{!!$errors->first('dimension_length')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('dimension_width'))has-danger @endif">
                            {!!Form::label('dimension_width','Width')!!}
                            {!!Form::text('dimension_width', old('dimension_width'), ['class'=>'form-control','placeholder'=>'Width'])!!}
                            @if($errors->has('dimension_width'))
                                <p class="help-block">{!!$errors->first('dimension_width')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('dimension_height'))has-danger @endif">
                            {!!Form::label('dimension_height','Height')!!}
                            {!!Form::text('dimension_height', old('dimension_height'), ['class'=>'form-control','placeholder'=>'Height'])!!}
                            @if($errors->has('dimension_height'))
                                <p class="help-block">{!!$errors->first('dimension_height')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('postal_company'))has-danger @endif">
                            {!!Form::label('postal_company','Postal Company')!!}
                            {!!Form::text('postal_company', old('postal_company'), ['class'=>'form-control','placeholder'=>'Postal Company'])!!}
                            @if($errors->has('postal_company'))
                                <p class="help-block">{!!$errors->first('postal_company')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('shipping_in_company'))has-danger @endif">
                            {!!Form::label('shipping_in_company','Shipping Company')!!}
                            {!!Form::text('shipping_in_company', old('shipping_in_company'), ['class'=>'form-control','placeholder'=>'Shipping Company'])!!}
                            @if($errors->has('shipping_in_company'))
                                <p class="help-block">{!!$errors->first('shipping_in_company')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('shipping_in_service'))has-danger @endif">
                            {!!Form::label('shipping_in_service','Shipping Service')!!}
                            {!!Form::text('shipping_in_service', old('shipping_in_service'), ['class'=>'form-control','placeholder'=>'Shipping Service'])!!}
                            @if($errors->has('shipping_in_service'))
                                <p class="help-block">{!!$errors->first('shipping_in_service')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('shipping_in_amount'))has-danger @endif">
                            {!!Form::label('shipping_in_amount','Shipping Amount')!!}
                            {!!Form::number('shipping_in_amount', old('shipping_in_amount'), ['class'=>'form-control','placeholder'=>'Shipping Amount'])!!}
                            @if($errors->has('shipping_in_amount'))
                                <p class="help-block">{!!$errors->first('shipping_in_amount')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('shipping_in_tracking'))has-danger @endif">
                            {!!Form::label('shipping_in_tracking','Shipping Tracking')!!}
                            {!!Form::text('shipping_in_tracking', old('shipping_in_tracking'), ['class'=>'form-control','placeholder'=>'Shipping Tracking'])!!}
                            @if($errors->has('shipping_in_tracking'))
                                <p class="help-block">{!!$errors->first('shipping_in_tracking')!!}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="">Shipment Images</label>
                        <label for="shipment_image" class="">
                            <span type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Add Pic</span>
                        </label>
                        <div class="row" id="shipment_image_div">
                            @if(isset($shipment))
                                @foreach($shipment->medias as $key=>$value)
                                    <div class="col-md-3 c-image div_{!! $value->id !!}">
                                        <img src="{!! asset($value->media_path.$value->media_name) !!}" alt=""
                                             width="100">
                                        <input type="hidden" name="image[]" value="{!! $value->id !!}">
                                        <button class="btn btn-sm btn-danger deleteImage" data-id="{!! $value->id !!}">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="ss-crop ss-crop--cover">
                            <div class="ss-crop__wrap">
                                <div class="ss-crop__preview">
                                    <div id="shipment_image-preview"></div>
                                </div>
                                <input type="hidden" name="" id="shipment_image_hidden">
                                <input type="file" accept="image/x-png,image/gif,image/jpeg" name="" value=""
                                       id="shipment_image" class="ss-crop__file">
                                <div class="ss-crop__select"></div>
                            </div>
                            <div class="">
                                <a href="javascript:;" style="margin-top: 60px !important;"
                                   class="ss-crop__submit">Save</a>
                                <a href="javascript:;" style="margin-top: 0px !important;"
                                   class="ss-crop__reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-8">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            {!!Form::close()!!}
            <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@stop

@section('contentFooter')
    <script src="{!! asset(mix('js/admin/vendor/ss-crop.js')) !!}"></script>
    <script>
        $('#user_id').select2({
            ajax: {
                url: adminAjaxURL + 'users/search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.items, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 3
        });
        $('#received_on').datetimepicker({
            format: 'dd-mm-yyyy hh:ii:ss',
            endDate: moment().format('DD-MM-YYYY HH:ii:ss')
        });
        $('#shipment_image').ssCrop({
            croppie: {
                viewport: {
                    width: 300,
                    height: 300,
                }
            },
            result: {
                size: {
                    width: 300,
                    format: 'jpeg'
                }
            }
        });
        $(document).on('click', '.ss-crop__submit', function (e) {
            e.preventDefault();
            if ($('#shipment_image_hidden').val() != '') {
                $.ajax({
                    dataType: 'json',
                    method: 'post',
                    url: adminAjaxURL + 'medias/shipment',
                    data: {
                        image: $('#shipment_image_hidden').val()
                    },
                    success: function (data) {
                        if (data['status']) {
                            str = '<div class="col-md-3 c-image div_' + data['id'] + '">' +
                                '<img src="' + data['media'] + '" alt="" >' +
                                '<input type="hidden" name="image[]" value="' + data['id'] + '">' +
                                '<button class="btn btn-sm btn-danger deleteImage" data-id="' + data['id'] + '"><i class="fa fa-times-circle"></i></button>' +
                                '</div>';
                            $('#shipment_image_div').append(str);
                            $('#shipment_image').ssCrop('resetCrop');
                        }
                    }
                });
            }
        });
        $(document).on('click', '.deleteImage', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                dataType: 'json',
                method: 'delete',
                url: adminAjaxURL + 'medias/' + $this.data('id'),
                success: function (data) {
                    if (data['status']) {
                        $('.div_' + $this.data('id')).remove();
                    }
                }
            });
        });

    </script>
@stop
