@extends('admin.layouts.master')

@section('contentHeader')
    <link rel="stylesheet" href="{!! asset(mix('css/admin/vendor/croppie.css')) !!}">
@stop

@section('page_title')
    @if(isset($item))
        Edit Shipment ({!! $shipment->name !!}) Item
    @else
        Create Shipment ({!! $shipment->name !!}) Item
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <!--begin::Form-->
                @if(isset($item))
                    {!!Form::model($item,['route'=>['admin.shipment-items.update',$shipment->id,$item->id],'method'=>'put','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @else
                    {!!Form::open(['route'=>['admin.shipment-items.store',$shipment->id],'class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @endif
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('item_name'))has-danger @endif">
                            {!!Form::label('item_name','Item Name')!!}
                            {!!Form::text('item_name', old('item_name'), ['class'=>'form-control','placeholder'=>'Item Name'])!!}
                            @if($errors->has('item_name'))
                                <p class="help-block">{!!$errors->first('item_name')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('qty'))has-danger @endif">
                            {!!Form::label('qty','Qty')!!}
                            {!!Form::number('qty', old('qty'), ['class'=>'form-control','placeholder'=>'Qty'])!!}
                            @if($errors->has('qty'))
                                <p class="form-control-feedback">{!!$errors->first('qty')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('amount'))has-danger @endif">
                            {!!Form::label('amount','Amount')!!}
                            {!!Form::number('amount', old('amount'), ['class'=>'form-control','placeholder'=>'Amount'])!!}
                            @if($errors->has('amount'))
                                <p class="form-control-feedback">{!!$errors->first('amount')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('desc'))has-danger @endif">
                            {!!Form::label('desc','Description')!!}
                            {!!Form::textarea('desc', old('desc'), ['class'=>'form-control','rows'=>3,'placeholder'=>'Description'])!!}
                            @if($errors->has('desc'))
                                <p class="form-control-feedback">{!!$errors->first('desc')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('tracking_number'))has-danger @endif">
                            {!!Form::label('tracking_number','Tracking Number')!!}
                            {!!Form::text('tracking_number', old('tracking_number'), ['class'=>'form-control','id'=>'tracking_number','placeholder'=>'Tracking Number'])!!}
                            @if($errors->has('tracking_number'))
                                <p class="help-block">{!!$errors->first('tracking_number')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('weight'))has-danger @endif">
                            {!!Form::label('weight','Weight')!!}
                            {!!Form::text('weight', old('weight'), ['class'=>'form-control','placeholder'=>'Weight'])!!}
                            @if($errors->has('weight'))
                                <p class="help-block">{!!$errors->first('weight')!!}</p>
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
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="">Shipment Images</label>
                        <div class="row" id="shipment_item_image_div">
                            @if(isset($item))
                                @foreach($item->medias as $key=>$value)
                                    <div class="col-md-2 div_{!! $value->id !!}">
                                        <img src="{!! asset($value->media_path.$value->media_name) !!}" alt=""
                                             width="100">
                                        <input type="hidden" name="image[]" value="{!! $value->id !!}">
                                        <button class="btn btn-sm btn-danger deleteImage" data-id="{!! $value->id !!}">
                                            Remove
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="ss-crop ss-crop--cover">
                            <div class="ss-crop__wrap">
                                <div class="ss-crop__preview">
                                    <div id="shipment_item_image-preview"></div>
                                </div>
                                <input type="hidden" name="" id="shipment_item_image_hidden">
                                <input type="file" accept="image/x-png,image/gif,image/jpeg" name="" value=""
                                       id="shipment_item_image" class="ss-crop__file">
                                <label for="shipment_item_image" class="ss-crop__select">
                                    <span type="button" class="btn btn-primary">Add Pic</span>
                                </label>
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
        $('#shipment_item_image').ssCrop({
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
            if ($('#shipment_item_image_hidden').val() != '') {
                $.ajax({
                    dataType: 'json',
                    method: 'post',
                    url: adminAjaxURL + 'medias/shipment_item',
                    data: {
                        image: $('#shipment_item_image_hidden').val()
                    },
                    success: function (data) {
                        if (data['status']) {
                            str = '<div class="col-md-2 div_' + data['id'] + '">' +
                                '<img src="' + data['media'] + '" alt="" width="100">' +
                                '<input type="hidden" name="image[]" value="' + data['id'] + '">' +
                                '<button class="btn btn-sm btn-danger deleteImage" data-id="' + data['id'] + '">Remove</button>' +
                                '</div>';
                            $('#shipment_item_image_div').append(str);
                            $('#shipment_item_image').ssCrop('resetCrop');
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