@extends('admin.layouts.master')

@section('contentHeader')
@stop

@section('page_title')
    @if(isset($country))
        Edit Country
    @else
        Create Country
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <!--begin::Form-->
                @if(isset($country))
                    {!!Form::model($country,['route'=>['admin.countries.update',$country->id],'method'=>'put','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @else
                    {!!Form::open(['route'=>'admin.countries.store','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @endif
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6 @if($errors->has('name'))has-danger @endif">
                            {!!Form::label('name','Name')!!}
                            {!!Form::text('name', old('name'), ['class'=>'form-control','placeholder'=>'Name'])!!}
                            @if($errors->has('name'))
                                <p class="form-control-feedback">{!!$errors->first('name')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-6 @if($errors->has('iso'))has-danger @endif">
                            {!!Form::label('iso','ISO')!!}
                            {!!Form::text('iso', old('iso'), ['class'=>'form-control','placeholder'=>'ISO'])!!}
                            @if($errors->has('iso'))
                                <p class="form-control-feedback">{!!$errors->first('iso')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6 @if($errors->has('country_code'))has-danger @endif">
                            {!!Form::label('country_code','Country Code')!!}
                            {!!Form::text('country_code', old('country_code'), ['class'=>'form-control','placeholder'=>'Country Code'])!!}
                            @if($errors->has('country_code'))
                                <p class="help-block">{!!$errors->first('country_code')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            {!!Form::label('suite_number','Suite Number')!!}
                            {!!Form::text('suite_number', old('suite_number'), ['class'=>'form-control','placeholder'=>'Suite Number'])!!}
                            @if($errors->has('suite_number'))
                                <p class="help-block">{!!$errors->first('suite_number')!!}</p>
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