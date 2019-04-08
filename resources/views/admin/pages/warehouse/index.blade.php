@extends('admin.layouts.master')

@section('contentHeader')
    <link rel="stylesheet" href="{!! asset(mix('css/admin/vendor/croppie.css')) !!}">
@stop

@section('page_title')
    Uk Warehouse address
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <!--begin::Form-->
                {!!Form::model($option,['route'=>'admin.uk.warehouse.address.store','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('uk_warehouse_address_line_1'))has-danger @endif">
                            {!!Form::label('uk_warehouse_address_line_1','Warehouse Address Line 1')!!}
                            {!!Form::text('uk_warehouse_address_line_1', old('uk_warehouse_address_line_1'), ['class'=>'form-control','placeholder'=>'Warehouse Address Line 1','maxlength'=>1000])!!}
                            @if($errors->has('uk_warehouse_address_line_1'))
                                <p class="form-control-feedback">{!!$errors->first('uk_warehouse_address_line_1')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('uk_warehouse_address_line_2'))has-danger @endif">
                            {!!Form::label('uk_warehouse_address_line_2','Warehouse Address Line 2')!!}
                            {!!Form::text('uk_warehouse_address_line_2', old('uk_warehouse_address_line_2'), ['class'=>'form-control','placeholder'=>'Warehouse Address Line 2','maxlength'=>1000])!!}
                            @if($errors->has('uk_warehouse_address_line_2'))
                                <p class="form-control-feedback">{!!$errors->first('uk_warehouse_address_line_2')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('uk_warehouse_country'))has-danger @endif">
                            {!!Form::label('uk_warehouse_country','Warehouse Country')!!}
                            {!!Form::text('uk_warehouse_country', old('uk_warehouse_country'), ['class'=>'form-control','placeholder'=>'Warehouse Country','maxlength'=>1000])!!}
                            @if($errors->has('uk_warehouse_country'))
                                <p class="form-control-feedback">{!!$errors->first('uk_warehouse_country')!!}</p>
                            @endif
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
@stop