@extends('frontend.layouts.blog')

@section('contentHeader')

@stop

@section('content')
    <ul class="blog-list ul-reset">
        <li>
            <div class="blog-list__wrap b-item card-shadow"><a class="b-item__cover" href="javascript:;"><img
                            src="{!! asset('img/temp-parcel.jpg') !!}" alt="Free Parcel Storage"/>
                    <h3 class="b-item__title">Happy customer, repeatable client</h3></a>
                <div class="viewmore">
                    <div class="viewmore__header">
                        <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                        </div>
                        <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                    </div>
                    <p class="viewmore__detail">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo
                        facilisis constituto ei.</p>
                    <div class="viewmore__link"><a href="{!! url('blog-detail') !!}">View more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="blog-list__wrap b-item card-shadow"><a class="b-item__cover" href="javascript:;"><img
                            src="{!! asset('img/temp-parcel.jpg') !!}" alt="Free Parcel Storage"/>
                    <h3 class="b-item__title">Happy customer, repeatable client</h3></a>
                <div class="viewmore">
                    <div class="viewmore__header">
                        <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                        </div>
                        <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                    </div>
                    <p class="viewmore__detail">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo
                        facilisis constituto ei.</p>
                    <div class="viewmore__link"><a href="{!! url('blog-detail') !!}">View more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="blog-list__wrap b-item card-shadow"><a class="b-item__cover" href="javascript:;"><img
                            src="{!! asset('img/temp-parcel.jpg') !!}" alt="Free Parcel Storage"/>
                    <h3 class="b-item__title">Happy customer, repeatable client</h3></a>
                <div class="viewmore">
                    <div class="viewmore__header">
                        <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                        </div>
                        <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                    </div>
                    <p class="viewmore__detail">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo
                        facilisis constituto ei.</p>
                    <div class="viewmore__link"><a href="{!! url('blog-detail') !!}">View more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="blog-list__wrap b-item card-shadow"><a class="b-item__cover" href="javascript:;"><img
                            src="{!! asset('img/temp-parcel.jpg') !!}" alt="Free Parcel Storage"/>
                    <h3 class="b-item__title">Happy customer, repeatable client</h3></a>
                <div class="viewmore">
                    <div class="viewmore__header">
                        <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                        </div>
                        <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                    </div>
                    <p class="viewmore__detail">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo
                        facilisis constituto ei.</p>
                    <div class="viewmore__link"><a href="{!! url('blog-detail') !!}">View more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="blog-list__wrap b-item card-shadow"><a class="b-item__cover" href="javascript:;"><img
                            src="{!! asset('img/temp-parcel.jpg') !!}" alt="Free Parcel Storage"/>
                    <h3 class="b-item__title">Happy customer, repeatable client</h3></a>
                <div class="viewmore">
                    <div class="viewmore__header">
                        <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                        </div>
                        <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                    </div>
                    <p class="viewmore__detail">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo
                        facilisis constituto ei.</p>
                    <div class="viewmore__link"><a href="{!! url('blog-detail') !!}">View more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="blog-list__wrap b-item card-shadow"><a class="b-item__cover" href="javascript:;"><img
                            src="{!! asset('img/temp-parcel.jpg') !!}" alt="Free Parcel Storage"/>
                    <h3 class="b-item__title">Happy customer, repeatable client</h3></a>
                <div class="viewmore">
                    <div class="viewmore__header">
                        <div class="vdate"><a href="javascript:;">February 14, 2018</a><a href="javascript:;">John</a>
                        </div>
                        <a class="vcomments" href="javascript:;"><i class="fas fa-comment"></i><span>50 comments</span></a>
                    </div>
                    <p class="viewmore__detail">Lorem ipsum dolor sit amet, ea rationibus deseruisse eum. Sed paulo
                        facilisis constituto ei.</p>
                    <div class="viewmore__link"><a href="{!! url('blog-detail') !!}">View more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <ul class="ul-reset pagination">
        <li><a class="arrows" href="javascipt:;"> <i class="fas fa-chevron-left fa-2x"></i></a></li>
        <li><a class="active" href="javascipt:;">1</a></li>
        <li><a href="javascipt:;">2</a></li>
        <li><a href="javascipt:;">3</a></li>
        <li><a class="arrows" href="javascipt:;"><i class="fas fa-chevron-right fa-2x"></i></a></li>
    </ul>
@stop

@section('contentFooter')

@stop
