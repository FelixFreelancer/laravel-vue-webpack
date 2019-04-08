@extends('frontend.layouts.dashboard')

@section('contentHeader')

@stop

@section('content')
    <div class="page-cover">
        <h1 class="page-cover__title">Estimate Shipping Cost</h1>
    </div>
    <section class="section section--estimate">
        <div class="ss-container">
          @include('frontend.partials.privacy_cookie')

            <h3 class="section--estimate__title">Price for shipping</h3>
            <h6 class="section--estimate__title">Please note that estimations are indicative.</h6>
            <div class="estimate">
                <div class="estimate__form">
                    {!! Form::open(['method'=>'get','url'=>'pricing']) !!}
                    <ul class="ul-reset estimate-input gpf-input gpf--input">
                        <li>
                            <label>Destination</label>
                            <div class="ss-select-wrap">
                                {!! Form::hidden('collection','GBR') !!}
                                {!!  Form::select('country', $country, Session::get('location')->iso_code ,[ 'class' => 'ss-select'] ) !!}
                            </div>
                        </li>
                        <li>
                            <label>Postcode</label>
                            {!!Form::text('postcode', Session::get('location')->postal_code )!!}
                        </li>
                        <li class="dimensionDiv">

                                @if(request('length'))
                                    @foreach(request('length') as $key=>$value)
                                    <div class="dimension_div_{!! $key !!}" >
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
                                    </div>
                                    @endforeach
                                @else
                                <div class="dimension_div_0">
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
                                </div>
                                @endif

                        </li>
                        <li class="weightDiv">

                                @if(request('weight'))
                                    @foreach(request('weight') as $key=>$value)
                                    <div class="weight_div_{!! $key !!}">
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
                                            @if($key == 0)

                                            <button class="button button--accent button--add addNewWeight">+</button>
                                            @else

                                            <button class="button button--accent button--add removeCurrentWeight" data-id="{!! $key !!}">-</button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <div class="weight_div_0">
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
                                </div>
                                @endif
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
                    <ul class="shipping_options ul-reset">

                    </ul>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@stop

@section('contentFooter')
    <script src="{!! asset('js/Cookies.js') !!}"></script>
    <script>
        var month = ["January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"];
        $(document).ready(function () {
            $.get(siteURL + "blog/?rest_route=/wp/v2/posts", function (data) {
                var count = 0;
                var blogHtml = '';
                $.each(data, function (index, value) {
                    count++;
                    // if (count > 3) {
                    //     return false;
                    // }
                    var postDate = new Date(value['date']);
                    // var postImage = 'https://www.globalparcelforward.com/img/green1.jpg';
                    // if (value['better_featured_image']) {
                    //     postImage = value['better_featured_image']['source_url'];
                    // }
                    blogHtml += '<div class="news-wrap">\n' +
                        '    <div class="news__item">\n' +
                        '        <div class="news__info n-info">\n' +
                        '            <div class="n-info__icon"><img src="' + value['better_featured_image']['source_url'] + '"/></div>\n' +
                        '            <div class="n-info__detail">\n' +
                        '                <h3 class="n-info__title">' + value['title']['rendered'] + '</h3>\n' +
                        '                <div class="n-info__date"><span>' + month[postDate.getMonth() + 1] + ' ' +
                        +postDate.getDate() + ', ' + postDate.getFullYear() + '</span><span>John Doe</span></div>\n' +
                        '            </div>\n' +
                        '        </div>\n' +
                        '        <div class="news__desc">\n' +
                        '            <p>Lorem ipsum dolor sit amet, consectetur adispiscing elit. Nulla dignissim ante eget eros\n' +
                        '                pulvinar id suscipit erat egestas. mauris augue, facilisis.</p>\n' +
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
        var i = {!! request('length') != NULL ?  count(request('length')) : 1 !!};
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
            console.log(data);
            var str = '';
            for (var index in data) {
                var option = data[index];
                str += '' +
                    '<li>' +
                    '    <div class="service__field"><div class="shipping__box"><img src="' + option['logo'] + '"/>' +
                    '        <div class="namerate"><span>' + option['name'] + '</span>' +
                    '            <ul class="ul-reset rating">' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '                <li><i class="fas fa-star"></i></li>' +
                    '            </ul>' +
                    '        </div></div>' +
                    '    </div>' +
                    '    <div class="price__field">' +
                    '        <div class="so-price">' +
                    '            <p>£' + option['total_price'] + '</p><span class="s-date"><label>TRANSIT TIME: </label>' + getDaydifference(option['estimated_delivery_date'])+ '</span>' +
                    '        </div>' +
                    '    </div>' +
                    '    <div class="features__field">' +
                    // '        ' + option['features'].replace(/<li>/g, '<div class="labelspan">').replace(/<\/li>/g, '</div>').replace(/<br \/>/g, '') + '' +
                    '        <ul class="feature-point ul-reset">'+
                    '            <li>' +
                    '                  <label>Tracking:</label><span>Yes</span>' +
                    '            </li>' +
                    '            <li>' +
                    '                  <label>Insurance:</label><span>Extra Cost</span>' +
                    '            </li>' +
                    '            <li>' +
                    '                  <label>Multi-piece:</label><span>Yes</span>' +
                    '            </li>' +
                    '        </ul>' +
                    '    </div>' +
                    // '    <td>' +
                    // '        <button data-service-name="' + option['service'] + '" data-logo="' + option['logo'] + '" data-name="' + option['name'] + '" data-price="' + option['price'] + '" data-shipment-id="' + shipment_id + '" class="button button--accent button--table selectShippingOption">Choose</button>' +
                    // '    </td>' +
                    '</li>';
            }
            return str;
        }

        function loadShippingOptions() {
            var weights = [];
            var heights = [];
            var widths = [];
            var lengths = [];


            $('[name^="weight["]').each(function (key, item) {
                weights.push($(item).val());
            });
            $('[name^="height["]').each(function (key, item) {
                heights.push($(item).val());
            });
            $('[name^="width["]').each(function (key, item) {
                widths.push($(item).val());
            });
            $('[name^="length["]').each(function (key, item) {
                lengths.push($(item).val());
            });


            var shipment = [];
            for (var index in weights) {
                if (weights[index] != '' && heights[index] != '' && widths[index] != '' && lengths[index] != '') {
                    var height=heights[index];
                    var width=widths[index];
                    var length=lengths[index];
                    var weight=weights[index];

                    if($('[name="dimension_type['+index+']"]:checked').val()=='2'){
                        height=height*2.54;
                        width=width*2.54;
                        length=length*2.54;
                    }
                    if($('[name="weight_type['+index+']"]:checked').val()=='2'){
                        weight=weight*0.454;
                    }
                    var item = {
                        weight: weight,
                        height: height,
                        width: width,
                        length: length,
                        value: 100,
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
                    collection: $('[name="collection"]').val(),
                    delivery: $('[name="country"]').val()
                },
                success: function (data) {
                    if(data['data'].length > 0){
                      $('.shipping_options').html(shippingOptionHtml(data['data']));
                    }else{
                      $('.shipping_options').html("<li>Please specify the dimensions of your package to enable us automatically display shipping options for "+$('[name="country"] option:selected').text()+". Shipping large items with irregular dimensions (100+ LxWxH) or weight (70kg and above)? Contact us for custom quotes as we cannot automatically display shipping options for large items and irregular dimensions at this time.</li>");
                    }
                }
            });
        }

        $(function () {
            loadShippingOptions();
        });

        function getDaydifference(estimateDate){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            var myDate = moment(estimateDate).format('YYYY/MM/DD');
            if(dd<10) {
                dd = '0'+dd
            }

            if(mm<10) {
                mm = '0'+mm
            }

            today = yyyy + '/' + mm + '/' + dd;


            // console.log(today);
            // console.log(myDate);
            var start = new Date(myDate);
            var end = new Date();
            var diffDays = new Date(start - end);
            days  = diffDays/1000/60/60/24;
            var estimateDate = (Math.round(days));
            return  estimateDate + " BUSINESS DAYS "
        };

    </script>
@stop
