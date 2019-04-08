@extends('admin.layouts.master')

@section('contentHeader')
    <link rel="stylesheet" href="{!! asset(mix('css/admin/vendor/croppie.css')) !!}">
@stop

@section('page_title')
    @if(isset($myprofile) && $myprofile)
        Profile
    @elseif(isset($admin))
        Edit Admin
    @else
        Create Admin
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <!--begin::Form-->
                @if(isset($myprofile) && $myprofile)
                    {!!Form::model($user,['route'=>'admin.myprofile.store','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @elseif(isset($admin))
                    {!!Form::model($admin,['route'=>['admin.admins.update',$admin->id],'method'=>'put','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @else
                    {!!Form::open(['route'=>'admin.admins.store','class'=>'m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed'])!!}
                @endif
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('first_name'))has-danger @endif">
                            {!!Form::label('first_name','First Name')!!}
                            {!!Form::text('first_name', old('first_name'), ['class'=>'form-control','placeholder'=>'First Name'])!!}
                            @if($errors->has('first_name'))
                                <p class="form-control-feedback">{!!$errors->first('first_name')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('last_name'))has-danger @endif">
                            {!!Form::label('last_name','Last Name')!!}
                            {!!Form::text('last_name', old('last_name'), ['class'=>'form-control','placeholder'=>'Last Name'])!!}
                            @if($errors->has('last_name'))
                                <p class="form-control-feedback">{!!$errors->first('last_name')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4 @if($errors->has('email'))has-danger @endif">
                            {!!Form::label('email','Email')!!}
                            @if(isset($myprofile) && $myprofile)
                                {!!Form::email('email', old('email'), ['class'=>'form-control','placeholder'=>'Email','disabled'])!!}
                            @else
                                {!!Form::email('email', old('email'), ['class'=>'form-control','placeholder'=>'Email'])!!}
                            @endif
                            @if($errors->has('email'))
                                <p class="form-control-feedback">{!!$errors->first('email')!!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4 @if($errors->has('password'))has-danger @endif">
                            {!!Form::label('password','Password')!!}
                            {!!Form::password('password', ['class'=>'form-control','placeholder'=>'Password'])!!}
                            @if($errors->has('password'))
                                <p class="form-control-feedback">{!!$errors->first('password')!!}</p>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            {!!Form::label('password_confirmation','Confirm Password')!!}
                            {!!Form::password('password_confirmation', ['class'=>'form-control','placeholder'=>'Confirm Password'])!!}
                            @if($errors->has('password_confirmation'))
                                <p class="form-control-feedback">{!!$errors->first('password_confirmation')!!}</p>
                            @endif
                        </div>
                    </div>
                </div>
                {{--<div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label for="">Profile Pic</label>
                        <div class="ss-crop ss-crop--cover">
                            <div class="ss-crop__wrap">
                                <div class="ss-crop__preview">
                                    <div id="profilePicture-preview"></div>
                                </div>
                                <input type="hidden" name="image" id="profilePicture_hidden">
                                <input type="file" accept="image/x-png,image/gif,image/jpeg" name="" value=""
                                       id="profilePicture" class="ss-crop__file">
                                <label for="profilePicture" class="ss-crop__select">
                                    @if(isset($user) && $user->profile!=null)
                                        <img src="{!! asset($user->profile_path.'/'.$user->profile) !!}"
                                             alt=""/>
                                    @else
                                        <img src="{!! asset('img/user4.jpg') !!}" alt=""/>
                                    @endif
                                    <span type="button" class="btn btn-primary">Change Pic</span>
                                </label>
                            </div>
                            <div class="">
                                <a href="javascript:;" class="ss-crop__reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>--}}
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
        $('#role').select2({
            placeholder: "Role"
        });
        $('#profilePicture').ssCrop({
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
    </script>
@stop