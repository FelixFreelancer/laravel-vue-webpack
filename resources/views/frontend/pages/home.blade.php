@extends('frontend.layouts.dashboard')

@section('contentHeader')

@stop

@section('content')
    <section class="section cover">
        <div class="box"><img src="{!! asset('img/cover-box.png') !!}" alt="Best Package Forwarding Service"/></div>
        <h1 class="cover__title">
            Shop UK stores <span
                    style="color: #0d486b; display: contents;">& </span><span>forward your parcels globally.</span>
        </h1>
        <a class="button button--accent button--cover" href="{!! url()->route('registration.plan') !!}" title="Pricing & Membership">Start Shipping
            Today</a>
            @include('frontend.partials.privacy_cookie')

    </section>
    <section class="section section--features" id="home_features">
        <div class="ss-container features">
            <ul class="ul-reset features__list">
                <li>
                    <div class="features__img"><img src="{!! asset('img/ft-1.png') !!}" alt="Register Account"/></div>
                    <h3 class="features__title">Register Account For Free</h3>
                    <p>It is easy. Choose a membership type and get your free UK shipping address.</p>
                </li>
                <li>
                    <div class="features__img"><img src="{!! asset('img/ft-2.png') !!}" alt="Shop In Your Favourite Stores"/></div>
                    <h3 class="features__title">Shop In Your Favourite Stores</h3>
                    <p>Shop any UK retailer, order your goods and have them shipped to your free UK address</p>
                </li>
                <li>
                    <div class="features__img"><img src="{!! asset('img/ft-3.png') !!}" alt="Your Parcel Hits Our Warehouse"/></div>
                    <h3 class="features__title">Your Parcel Hits Our Warehouse</h3>
                    <p>We notify you automatically, once your parcel is delivered to our warehouse</p>
                </li>
                <li>
                    <div class="features__img"><img src="{!! asset('img/ft-4.png') !!}" alt="We Ship Your Parcels"/></div>
                    <h3 class="features__title">We Ship Your Parcels</h3>
                    <p> Choose how to ship your package and we forward it to your address globally. </p>
                </li>
            </ul>
        </div>
    </section>
    <section class="section section--video">
        <div class="ss-container">
            <div class="video">
                <div class="video__vid">
                            <script src="https://fast.wistia.com/embed/medias/dp41ei73ju.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><span class="wistia_embed wistia_async_dp41ei73ju popover=true popoverAnimateThumbnail=true videoFoam=true" style="display:inline-block;height:100%;width:100%">&nbsp;</span></div></div>
                </div>
                <div class="video__info"><img src="{!! asset('img/vinfo-bg.png') !!}" alt="Global Parcel Forward"/>
                    <div class="video-txt">
                        <h3 class="video__title">Watch this video to see how it works</h3>
                        <p>Shoppers around the world use our service to forward parcels globally. We are the only parcel
                            forwarding company that has the resources, expertise, customer dedication and global
                            experience to make shopping and shipping from the UK fast, dependable, reliable and
                            affordable.</p>
                        <div class="actions">
                            <a class="button button--primary" href="{!! url()->route('registration.plan') !!}" title="Pricing & Membership">
                                View membership
                            </a>
                            <a class="button button--accent" href="{!! url()->route('registration.plan') !!}" title="Sign-in to your GPF Account">
                                Sign up for free
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--estimate">
        <div class="ss-container">
            <div class="estimate">
                <div class="estimate__cover">
                    <div class="quick"><i class="fas fa-calculator fa-4x" style="color:#063c5d;"></i>
                        <h4 class="quick__title"><span>Get a quick </span><span>estimate</span></h4>
                    </div>
                    <div class="offer">
                        <p class="offer__text">Save up to 80% on shipping costs!</p>
                    </div>
                </div>
                <div class="estimate__form">
                    {!! Form::open(['method'=>'get','url'=>'pricing']) !!}
                    <ul class="ul-reset estimate-input gpf-input">
                        <li>
                            <label>Destination</label>
                            <div class="ss-select-wrap">
                                {!! Form::hidden('collection','GBR') !!}
                                {!!  Form::select('country', $country, Session::get('location')->short_code ,[ 'class' => 'ss-select'] ) !!}
                            </div>
                        </li>
                        <li>
                            <label>Postcode</label>
                            {!!Form::text('postcode', Session::get('location')->postal_code )!!}
                        </li>
                        <li class="dimensionDiv">
                            <div>
                                @if(request('length'))
                                    @foreach(request('length') as $key=>$value)
                                        <label>Dimension</label>
                                        <ul class="ul-reset dimension">
                                            <li>
                                                <label>L</label>
                                                {!! Form::text('length['.$key.']',request('length.'.$key)) !!}
                                            </li>
                                            <li>
                                                <label>W</label>
                                                {!! Form::text('width['.$key.']',request('width.'.$key)) !!}
                                            </li>
                                            <li>
                                                <label>H</label>
                                                {!! Form::text('height['.$key.']',request('height.'.$key)) !!}
                                            </li>
                                            <li class="no">
                                                <ul class="ul-reset radio">
                                                    <li>
                                                        @if(isset(request('dimension_type')[$key]))
                                                            @if(request('dimension_type')[$key]==1)
                                                                <input type="radio" name="dimension_type[{!! $key !!}]"
                                                                       value="1" id="cm{!! $key !!}" checked="checked">
                                                            @else
                                                                <input type="radio" name="dimension_type[{!! $key !!}]"
                                                                       value="1" id="cm{!! $key !!}">
                                                            @endif
                                                        @else
                                                            <input type="radio" name="dimension_type[{!! $key !!}]"
                                                                   value="1" id="cm{!! $key !!}" checked="checked">
                                                        @endif
                                                        <label for="cm{!! $key !!}">cm</label>
                                                    </li>
                                                    <li>
                                                        @if(isset(request('dimension_type')[$key]))
                                                            @if(request('dimension_type')[$key]==2)
                                                                <input type="radio" name="dimension_type[{!! $key !!}]"
                                                                       value="2" id="inch{!! $key !!}" checked="checked">
                                                            @else
                                                                <input type="radio" name="dimension_type[{!! $key !!}]"
                                                                       value="2" id="inch{!! $key !!}">
                                                            @endif
                                                        @else
                                                            <input type="radio" name="dimension_type[{!! $key !!}]"
                                                                   value="2" id="inch{!! $key !!}" checked="checked">
                                                        @endif
                                                        <label for="inch{!! $key !!}">inch</label>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    @endforeach
                                @else
                                    <label>Dimension</label>
                                    <ul class="ul-reset dimension">
                                        <li>
                                            <label>L</label>
                                            <input name="length[0]" type="text"/>
                                        </li>
                                        <li>
                                            <label>W</label>
                                            <input name="width[0]" type="text"/>
                                        </li>
                                        <li>
                                            <label>H</label>
                                            <input name="height[0]" type="text"/>
                                        </li>
                                        <li class="no">
                                            <ul class="ul-reset radio">
                                                <li>
                                                    <input type="radio" id="cm0" value="1" name="dimension_type[0]"
                                                           checked="checked"/>
                                                    <label for="cm0">cm</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="inch0" value="2"
                                                           name="dimension_type[0]"/>
                                                    <label for="inch0">inch</label>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </li>
                        <li class="weightDiv">
                            <div>
                                @if(request('weight'))
                                    @foreach(request('weight') as $key=>$value)
                                        <label>Weight</label>
                                        <div class="weight">
                                            {!! Form::text('weight['.$key.']',request('weight')[$key]) !!}
                                            <ul class="ul-reset radio">
                                                <li>
                                                    @if(isset(request('weight_type')[$key]))
                                                        @if(request('weight_type')[$key]==1)
                                                            <input type="radio" name="weight_type[{!! $key !!}]"
                                                                   value="1" id="kg{!! $key !!}" checked="checked">
                                                        @else
                                                            <input type="radio" name="weight_type[{!! $key !!}]"
                                                                   value="1" id="kg{!! $key !!}">
                                                        @endif
                                                    @else
                                                        <input type="radio" name="weight_type[{!! $key !!}]"
                                                               value="1" id="kg{!! $key !!}" checked="checked">
                                                    @endif
                                                    <label for="kg{!! $key !!}">kg</label>
                                                </li>
                                                <li>
                                                    @if(isset(request('weight_type')[$key]))
                                                        @if(request('weight_type')[$key]==2)
                                                            <input type="radio" name="weight_type[{!! $key !!}]"
                                                                   value="2" id="lbs{!! $key !!}" checked="checked">
                                                        @else
                                                            <input type="radio" name="weight_type[{!! $key !!}]"
                                                                   value="2" id="lbs{!! $key !!}">
                                                        @endif
                                                    @else
                                                        <input type="radio" name="weight_type[{!! $key !!}]"
                                                               value="2" id="lbs{!! $key !!}" checked="checked">
                                                    @endif
                                                    <label for="lbs{!! $key !!}">lbs</label>
                                                </li>
                                            </ul>
                                            <button class="button button--accent button--add addNewWeight">+</button>
                                        </div>
                                    @endforeach
                                @else
                                    <label>Weight</label>
                                    <div class="weight">
                                        <input name="weight[0]" type="text"/>
                                        <ul class="ul-reset radio">
                                            <li>
                                                <input type="radio" id="kg0" value="1" name="weight_type[0]"
                                                       checked="checked"/>
                                                <label for="kg0">kg</label>
                                            </li>
                                            <li>
                                                <input type="radio" id="lbs0" value="2" name="weight_type[0]"/>
                                                <label for="lbs0">lbs</label>
                                            </li>
                                        </ul>
                                        <button class="button button--accent button--add addNewWeight">+</button>
                                    </div>
                                @endif
                            </div>
                        </li>
                        <li>
                            <button class="button button--primary" type="submit">
                                <span>compare prices</span><i class="fas fa-search fa-2x"></i>
                            </button>
                        </li>
                        <li>
                            <a class="button button--accent"
                               @if(auth()->check())
                               href="{!! url()->route('users.profile.index') !!}"
                               @else
                               href="{!! url()->route('registration.plan') !!}"
                                    @endif
                            >
                                <span>Start Shipping now</span>
                            </a>
                        </li>
                    </ul>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
    <section class="section section--store">
        <div class="ss-container stores">
            <h3 class="section__title">Shop On Favourite Stores</h3>
            <p class="section__desc">More than 150+ UK Favorite Stores & Brands to shop from. Get great deals from top
                retailers like Amazon, Zara, Ebay, DiscountIsland.Co.Uk and more for top brands, flash sales, coupons
                and discounts you can find only in the UK. </p>
            <ul class="store-list ul-reset">
                <li><img src="{!! asset('img/1.jpg') !!}" alt="Argos"/></li>
                <li><img src="{!! asset('img/2.jpg') !!}" alt="Disney"/></li>
                <li><img src="{!! asset('img/3.jpg') !!}" alt="Discount"/></li>
                <li><img src="{!! asset('img/4.jpg') !!}" alt="Nike"/></li>
                <li><img src="{!! asset('img/5.jpg') !!}" alt="Harrods"/></li>
                <li><img src="{!! asset('img/6.jpg') !!}" alt="Currys"/></li>
                <li><img src="{!! asset('img/7.jpg') !!}" alt="Liberty"/></li>
                <li><img src="{!! asset('img/8.jpg') !!}" alt="next"/></li>
                <li><img src="{!! asset('img/9.jpg') !!}" alt="zulily"/></li>
                <li><img src="{!! asset('img/10.jpg') !!}" alt="Tesco"/></li>
                <li><img src="{!! asset('img/11.jpg') !!}" alt="Apple"/></li>
                <li><img src="{!! asset('img/12.jpg') !!}" alt="Adidas"/></li>
                <li><img src="{!! asset('img/13.jpg') !!}" alt="John Lewis"/></li>
                <li><img src="{!! asset('img/14.jpg') !!}" alt="Toys"/></li>
                <li><img src="{!! asset('img/15.jpg') !!}" alt="Laura Ashley"/></li>
                <li><img src="{!! asset('img/16.jpg') !!}" alt="ebay"/></li>
                <li><img src="{!! asset('img/17.jpg') !!}" alt="Zara"/></li>
                <li><img src="{!! asset('img/18.jpg') !!}" alt="mothercare"/></li>
                <li><img src="{!! asset('img/19.jpg') !!}" alt="Book People"/></li>
                <li><img src="{!! asset('img/20.jpg') !!}" alt="Supreme"/></li>
                <li><img src="{!! asset('img/21.jpg') !!}" alt="Zalando"/></li>
                <li><img src="{!! asset('img/22.jpg') !!}" alt="amazon"/></li>
                <li><img src="{!! asset('img/23.jpg') !!}" alt="Clarks"/></li>
                <li><img src="{!! asset('img/24.jpg') !!}" alt="SelfRidges&CO"/></li>
                <li><img src="{!! asset('img/25.jpg') !!}" alt="Boots"/></li>
                <li><img src="{!! asset('img/26.jpg') !!}" alt="Workshop"/></li>
                <li><img src="{!! asset('img/27.jpg') !!}" alt="M & S"/></li>
                <li><img src="{!! asset('img/28.jpg') !!}" alt="H & M"/></li>
                <li><img src="{!! asset('img/29.jpg') !!}" alt="SeaSalt"/></li>
                <li><img src="{!! asset('img/30.jpg') !!}" alt="Game"/></li>
            </ul>
            <a class="button button--accent" href="{!! url()->route('registration.plan') !!}" title="Pricing & Membership">Start Shipping Today</a>
        </div>
    </section>
    <section class="section section--customer">
        <div class="ss-container customer">
            <h3 class="section__title">Our Happy Customers</h3>
            <p class="section__desc">Our members love our top-rated service and how easy and worry-free we make shopping and shipping from the United Kingdom. See reviews from our users on how we stack up against other global international shipping providers.</p>
            <div class="reviews">
                <div class="reviews__total total">
                    <h3 class="total__title">Excellent </h3>
                    <ul class="ul-reset ratings">
                        <li><i class="fas fa-star"></i></li>
                        <li><i class="fas fa-star"></i></li>
                        <li><i class="fas fa-star"></i></li>
                        <li><i class="fas fa-star"></i></li>
                        <li><i class="fas fa-star"></i></li>
                    </ul>
                    <p class="total__info">Based on <b>TOP</b> reviews <br/> See some of the reviews here.</p><img
                            src="{!! asset('img/gpf-reviews.png') !!}" alt="Customer Reviews"/>
                </div>
                <div class="reviews__single">
                    <div class="review-slider">
                        <div class="single">
                            <div class="single__top">
                                <ul class="ul-reset ratings small">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                </ul>
                                <div class="single__top__date"><span>21 June</span></div>
                            </div>
                            <h3 class="single__title">Excellent Service</h3>
                            <p class="single__desc">Good and smooth transaction, helpful
                                                    delivery guys. I am very satisfied with
                                                    the service and will continue to use
                                                    Globalparcelforward.
                            </p>
                        </div>
                        <div class="single">
                            <div class="single__top">
                                <ul class="ul-reset ratings small">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                </ul>
                                <div class="single__top__date"><span>12 April</span></div>
                            </div>
                            <h3 class="single__title">Thank you</h3>
                            <p class="single__desc">Package arrived early and as described,
                                                    the tracking was very helpful and the overall
                                                    service was faster than other similar
                                                    services I had used in the past.
                            </p>
                        </div>
                        <div class="single">
                            <div class="single__top">
                                <ul class="ul-reset ratings small">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                </ul>
                                <div class="single__top__date"><span>18 May</span></div>
                            </div>
                            <h3 class="single__title">Best of the best</h3>
                            <p class="single__desc">Globalparcelforward is the best
                                                    of the best. best delivery, best price,
                                                    best customer service, best overall user
                                                    experience. I highly recommend.
                            </p>
                        </div>
                        <div class="single">
                            <div class="single__top">
                                <ul class="ul-reset ratings small">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                </ul>
                                <div class="single__top__date"><span>21 June</span></div>
                            </div>
                            <h3 class="single__title">Excellent Service</h3>
                            <p class="single__desc">Good and smooth transaction, helpful
                                                    delivery guys. I am very satisfied with
                                                    the service and will continue to use
                                                    Globalparcelforward.
                            </p>
                        </div>
                        <div class="single">
                            <div class="single__top">
                                <ul class="ul-reset ratings small">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                </ul>
                                <div class="single__top__date"><span>12 April</span></div>
                            </div>
                            <h3 class="single__title">Thank you</h3>
                            <p class="single__desc">Package arrived early and as described,
                                                    the tracking was very helpful and the overall
                                                    service was faster than other similar
                                                    services I had used in the past.
                            </p>
                        </div>
                        <div class="single">
                            <div class="single__top">
                                <ul class="ul-reset ratings small">
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                    <li><i class="fas fa-star"></i></li>
                                </ul>
                                <div class="single__top__date"><span>18 May</span></div>
                            </div>
                            <h3 class="single__title">Best of the best</h3>
                            <p class="single__desc">Globalparcelforward is the best
                                                    of the best. best delivery, best price,
                                                    best customer service, best overall user
                                                    experience. I highly recommend.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--news">
        <div class="ss-container news">
            <h3 class="section__title">Latest News</h3>
            <div class="news__slider newsSlider">
            </div>
        </div>
    </section>
    @include('frontend.popups.subscription')
@stop

@section('contentFooter')
    <script>
        var month = ["January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"];
        $(document).ready(function () {

          // $("#subscription").modal("show");
          // $(".subscription_error").hide();
          //
          // $(document).on('submit','#subscriptionForm', function (e) {
          //     e.preventDefault();
          //
          //     $.ajax({
          //         dataType: 'json',
          //         method: 'post',
          //         url: ajaxURL + 'subscribe',
          //         data: {
          //             email: $('#email').val()
          //         },
          //         success: function (rst) {
          //               $("#subscription").modal("hide");
          //         },
          //         error: function (req, status, err ) {
          //           $(".subscription_error").show();
          //           $('.subscription_error').html(req.responseJSON.data.error.email[0]);
          //         }
          //     });
          // });

            $.get(siteURL + "blog/?rest_route=/wp/v2/posts", function (data) {
                var count = 0;
                var blogHtml = '';

                $.each(data, function (index, value) {
                    count++;
                    // if (count > 3) {
                    //     return false;
                    // }
                    var postDate = new Date(value['date']);
                    // var postImage = 'http://hello.trackdrive.net/wp-content/uploads/2018/02/green1.jpg';
                    // if (value['better_featured_image']) {
                    //     postImage = value['better_featured_image']['source_url'];
                    // }


                      blogHtml += '<div class="news-wrap">\n' +
                      '    <div class="news__item">\n' +
                      '        <div class="news__info n-info">\n' +
                      '            <div class="n-info__icon"><img src="' + value['better_featured_image']['source_url'] + '"/></div>\n' +
                      '            <div class="n-info__detail">\n' +
                      '                <h3 class="n-info__title">' + value['title']['rendered'] + '</h3>\n' +
                      '                <div class="n-info__date"><span>' + month[postDate.getMonth()] + ' ' +
                      +postDate.getDate() + ', ' + postDate.getFullYear() + '</span><span class="author_name'+index+'"></span></div>\n' +
                      '            </div>\n' +
                      '        </div>\n' +
                      '        <div class="news__desc">\n' +
                      '            <p>'+value['content']['rendered'].substr(0,222) +'</p>\n' +
                      '        </div>\n' +
                      '        <div class="news__action"><a class="button button--accent button--small" href="' + value['link'] + '">Read\n' +
                      '                More</a></div>\n' +
                      '    </div>\n' +
                      '</div>';
                    // blogHtml += `<li>
                    //   <div class="article">
                    //     <a class="article__image" href="+value['link']+">
                    //       <div class="ratio-img">
                    //         <img src="+postImage+"/>
                    //       </div>
                    //     </a>
                    //     <div class="article__content">
                    //       <a class="article__title" href="+value['link']+">` + value['title']['rendered'] + `</a>
                    //       <div class="article__footer">
                    //         <a class="article__date" href="+value['link']+">` + month[postDate.getMonth() + 1] + `  ` +
                    //     +postDate.getDate() + `, ` +
                    //     +postDate.getFullYear() + `</a>
                    //       </div>
                    //     </div>
                    //   </div>
                    // </li>`;

                    $.get(value['_links']['author'][0]['href'], function(data) {
                      $(".author_name"+index).text(data['name']);
                    });

                });

                $('.newsSlider').html(blogHtml);
                // $('.blog__list').removeClass('td-loader');
                $(".news__slider").slick({
                    slidesToShow: 3,
                    arrows: true,
                    dots: false,
                    responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2
                        }
                    }, {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            arrows: false,
                            dots: true
                        }
                    }]
                });
            });
        });

        if (Cookies.get('gpf_cookie_accept') == 'true') {
            $('.cookieAcceptDiv').remove();
        }
        $(document).on('click', '.cookieClose', function (e) {
            e.preventDefault();
            Cookies.set('gpf_cookie_accept', 'true', {expiry: 86400});
        });
        var i = 0;
        $(document).on('click', '.addNewWeight', function (e) {
            e.preventDefault();
            i++;
            str = '<div class="weight_div_' + i + '">' +
                '   <label>Weight</label>' +
                '   <div class="weight">' +
                '       <input type="text" name="weight[' + i + ']"/>' +
                '       <ul class="ul-reset radio">' +
                '           <li>' +
                '               <input type="radio" id="kg'+ i +'" value="1" name="weight_type[' + i + ']" checked="checked"/>' +
                '               <label for="kg'+ i +'">kg</label>' +
                '           </li>' +
                '           <li>' +
                '               <input type="radio" id="lbs'+ i +'" value="2" name="weight_type[' + i + ']"/>' +
                '               <label for="lbs'+ i +'">lbs</label>' +
                '           </li>' +
                '       </ul>' +
                '       <button class="button button--accent button--add removeCurrentWeight" data-id="' + i + '">-</button>' +
                '   </div>' +
                '</div>';
            $('.weightDiv').append(str);
            str = '<div class="dimension_div_' + i + '">' +
                '    <label>Dimension</label>' +
                '    <ul class="ul-reset dimension">' +
                '        <li>' +
                '            <label>L</label>' +
                '            <input name="length[' + i + ']" type="text"/>' +
                '        </li>' +
                '        <li>' +
                '            <label>W</label>' +
                '            <input name="width[' + i + ']" type="text"/>' +
                '        </li>' +
                '        <li>' +
                '            <label>H</label>' +
                '            <input name="height[' + i + ']" type="text"/>' +
                '        </li>' +
                '        <li class="no">' +
                '            <ul class="ul-reset radio">' +
                '                <li>' +
                '                    <input type="radio" id="cm'+ i +'" value="1" name="dimension_type[' + i + ']" checked="checked"/>' +
                '                    <label for="cm'+ i +'">cm</label>' +
                '                </li>' +
                '                <li>' +
                '                    <input type="radio" id="inch'+ i +'" value="2" name="dimension_type[' + i + ']"/>' +
                '                    <label for="inch'+ i +'">inch</label>' +
                '                </li>' +
                '            </ul>' +
                '        </li>' +
                '    </ul>' +
                '</div>';
            $('.dimensionDiv').append(str);
        });
        $(document).on('click', '.removeCurrentWeight', function (e) {
            e.preventDefault();
            $('.dimension_div_' + $(this).data('id')).remove();
            $('.weight_div_' + $(this).data('id')).remove();
        });

        function shippingOptionHtml(data) {
            var str = '';
            for (var index in data) {
                var option = data[index];
                str += '' +
                    '<tr>' +
                    '    <td><div class="shipping__box"><img src="' + option['logo'] + '"/>' +
                    '        <div class="namerate"><span>' + option['name'] + '</span>' +
                    '            <ul class="ul-reset rating">' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '            </ul>' +
                    '        </div></div>' +
                    '    </td>' +
                    '    <td>' +
                    '        <div class="so-price">' +
                    '            <p>£' + option['price'] + '</p><span>(' + moment(option['estimated_delivery_date']).format('MM/DD/YYYY') + ')</span>' +
                    '        </div>' +
                    '    </td>' +
                    '    <td>' +
                    '        ' + option['features'].replace(/<li>/g, '<div class="labelspan">').replace(/<\/li>/g, '</div>').replace(/<br \/>/g, '') + '' +
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
                    // '    <td>' +
                    // '        <button data-service-name="' + option['service'] + '" data-logo="' + option['logo'] + '" data-name="' + option['name'] + '" data-price="' + option['price'] + '" data-shipment-id="' + shipment_id + '" class="button button--accent button--table selectShippingOption">Choose</button>' +
                    // '    </td>' +
                    '</tr>';
            }
            return str;
        }

        function loadShippingOptions() {
            var weights = [];
            $('[name^="weight["]').each(function (key, item) {
                weights.push($(item).val());
            });
            var height = $('[name^="height["]');
            var width = $('[name^="width[');
            var length = $('[name^="length["]');
            var value = 100;
            var shipment = [];
            for (var index in weights) {
                if (weights[index] != '' && $(height[index]).val() != '' && $(width[index]).val() != '' && $(length[index]).val() != '') {
                    var item = {
                        Weight: weights[index],
                        Height: $(height[index]).val(),
                        Width: $(width[index]).val(),
                        Length: $(length[index]).val(),
                        Value: 100,
                    };
                    shipment.push(item);
                }
            }
            $.ajax({
                dataType: 'json',
                method: 'post',
                url: siteURL + 'shipping-options',
                data: {
                    shipment: shipment,
                    Collection: $('[name="collection"]').val(),
                    Delivery: $('[name="country"]').val()
                },
                success: function (data) {
                    $('.shipping_options').html(shippingOptionHtml(data));
                }
            });
        }

        $(function () {
            loadShippingOptions();
        });
    </script>
@stop
