@extends('frontend.layouts.common')

@section('contentHeader')

@stop

@section('content')
    <div class="page-cover">
        <h3 class="page-cover__title">Sorry, Page Not Found - 404</h3>
    </div>
    <div class="ss-container">
        <ul class="ul-reset sitemap">
            <li>
                <div class="sitemap__col">
                    <h3 style="text-align:center" class="sitemap__title card-shadow">That's all we know! <br> <br> But, it's nothing to worry about, you can retrace your steps and head back home! :)</h3>
                    <ul class="ul-reset sitemap__links">
                        <li style="text-align:center"> <a href="{!! url('') !!}" title="Global Parcel Forward">Back to home!</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
@stop

@section('contentFooter')

@stop