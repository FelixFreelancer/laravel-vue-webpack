@extends('frontend.layouts.profile')

@section('contentHeader')

@stop

@section('content')
    <div class="dashboard__content dashboard__content--pd card-shadow">
        <div class="rect"></div>
        <div class="shopper-wrap">
            <h3 class="dashboard__content__title">Personal Shopper</h3>
            <p class="shopper__text">Lorem ipsum dolor sit amet, consectetur adispiscing elit. Nulla dignissim ante eget
                eros pulvinar id suscipit erat egestas.
                <Aliquam>erat volitpat. Mauris eu arcu ultricies ipsum eleifend rhoncus ut in felis. Nam congue, massa
                    sed tempus tempor,
                </Aliquam>
                <massa>elit ornare lectus, et adispiscing augue neque in ante. Vivamus porta dictum dapibus. Nunc mauris
                    augue, facilisis a
                </massa>
                <consectetur>a, fermentum at augue.</consectetur>
            </p>
            {!!Form::open(['route'=>'users.shopper.store'])!!}
            <div class="shopper__box">
                <h4 class="shopper__title">Request Shopping</h4>
                <div class="shopper-form">
                    @if(old('store_name'))
                        @foreach(old('store_name') as $key=>$value)

                            <ul class="ul-reset">
                                <li>
                                    <button class="btn btn-danger removeItem">
                                        <svg fill="#fff" height="24" viewBox="0 0 24 24" width="24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                            <path d="M0 0h24v24H0z" fill="none"/>
                                        </svg>
                                        <span>Remove</span>
                                    </button>
                                </li>
                                <li class="gpf-input one-half-wrap">
                                    <div class="one-half-input">
                                        {!!Form::label('store_name','Store Name')!!}
                                        {!!Form::text('store_name['.$key.']', old('store_name.'.$key), ['placeholder'=>'Store Name'])!!}
                                        @if($errors->has('store_name.'.$key))
                                            <p class="help-block error">{!! $errors->first('store_name.'.$key) !!}</p>
                                        @endif
                                    </div>
                                    <div class="one-half-input">
                                        {!!Form::label('direct_link','Item Direct Link')!!}
                                        {!!Form::text('direct_link['.$key.']', old('direct_link.'.$key), ['placeholder'=>'Item Direct Link'])!!}
                                        @if($errors->has('direct_link.'.$key))
                                            <p class="help-block error">{!! $errors->first('direct_link.'.$key) !!}</p>
                                        @endif
                                    </div>
                                </li>
                                <li class="gpf-input one-half-wrap">
                                    <div class="one-half-input">
                                        {!!Form::label('item_name','Item Name')!!}
                                        {!!Form::text('item_name['.$key.']', old('item_name.'.$key), ['placeholder'=>'Item Name'])!!}
                                        @if($errors->has('item_name.'.$key))
                                            <p class="help-block error">{!! $errors->first('item_name.'.$key) !!}</p>
                                        @endif
                                    </div>
                                    <div class="one-half-input">
                                        <ul class="ul-reset sub-field">
                                            <li>
                                                {!!Form::label('user_price_currency','Price')!!}
                                                <div class="sub__box">
                                                    <div class="sub__field">
                                                        {!!Form::select('user_price_currency['.$key.']',$currencies,old('user_price_currency.'.$key))!!}
                                                        @if($errors->has('user_price_currency.'.$key))
                                                            <p class="help-block error">{!! $errors->first('user_price_currency.'.$key) !!}</p>
                                                        @endif
                                                    </div>
                                                    <div class="sub__field">
                                                        {!!Form::text('user_price['.$key.']', old('user_price.'.$key), ['placeholder'=>'Price'])!!}
                                                        @if($errors->has('user_price.'.$key))
                                                            <p class="help-block error">{!! $errors->first('user_price.'.$key) !!}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                {!!Form::label('color','Color')!!}
                                                {!!Form::text('color['.$key.']', old('color.'.$key), ['placeholder'=>'Color'])!!}
                                                @if($errors->has('color.'.$key))
                                                    <p class="help-block error">{!! $errors->first('color.'.$key) !!}</p>
                                                @endif
                                            </li>
                                            <li>
                                                {!!Form::label('quantity','Qty')!!}
                                                {!!Form::text('quantity['.$key.']', old('quantity.'.$key), ['placeholder'=>'Qty'])!!}
                                                @if($errors->has('quantity.'.$key))
                                                    <p class="help-block error">{!! $errors->first('quantity.'.$key) !!}</p>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    @else
                        <ul class="ul-reset">
                            <li>
                                <button class="btn-danger removeItem">
                                    <i class="material-icons">close</i>
                                </button>
                            </li>
                            <li class="gpf-input one-half-wrap">
                                <div class="one-half-input">
                                    {!!Form::label('store_name','Store Name')!!}
                                    {!!Form::text('store_name[]', '', ['placeholder'=>'Store Name'])!!}
                                </div>
                                <div class="one-half-input">
                                    {!!Form::label('direct_link','Item Direct Link')!!}
                                    {!!Form::text('direct_link[]', '', ['placeholder'=>'Item Direct Link'])!!}
                                </div>
                            </li>
                            <li class="gpf-input one-half-wrap">
                                <div class="one-half-input">
                                    {!!Form::label('item_name','Item Name')!!}
                                    {!!Form::text('item_name[]', '', ['placeholder'=>'Item Name'])!!}
                                </div>
                                <div class="one-half-input">
                                    <ul class="ul-reset sub-field">
                                        <li>
                                            {!!Form::label('user_price_currency','Price')!!}
                                            <div class="sub__box">
                                                <div class="sub__field">
                                                    {!!Form::select('user_price_currency[]',$currencies,'')!!}
                                                </div>
                                                <div class="sub__field">
                                                    {!!Form::text('user_price[]', '', ['placeholder'=>'Price'])!!}
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            {!!Form::label('color','Color')!!}
                                            {!!Form::text('color[]', '', ['class'=>'form-control','placeholder'=>'Color'])!!}
                                        </li>
                                        <li>
                                            {!!Form::label('quantity','Qty')!!}
                                            {!!Form::text('quantity[]', '', ['class'=>'form-control','placeholder'=>'Qty'])!!}
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @endif
                    <div class="shopper-action">
                        <button class="button--primary addNewItem">Add More</button>
                        <button class="button--accent">Get A Quote</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="shopper__box">
            <h4 class="dashboard__content__title">Quotation </h4>
            <div class="shopper__table__wrap">
                @foreach($quotations as $key=>$value)
                    <h6>Quotation {!! $value->quotation_number !!}</h6>
                    <table class="shopper__table">
                        <thead>
                        <tr>
                            <th>Shop name</th>
                            <th>Item Description</th>
                            <th>Direct Link</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                          $total = 0;
                          $currency = '';
                          $price = '';
                        ?>
                        @foreach($value->items as $item)
                            <tr>
                                <td>{!! $item->store_name !!}</td>
                                <td>{!! $item->item_name !!} (Color: {!! $item->color !!})</td>
                                <td><a href="{!! $item->direct_link !!}">click here</a></td>
                                <td>
                                    @if($item->status!=null)
                                        {!! config('site.quotation_status.'.$item->status) !!}
                                    @else
                                        Pending
                                    @endif
                                </td>
                                <td>{!! $item->user_price_currency.$item->user_price !!}</td>
                                <td>{!! $item->quantity !!}</td>
                                <td>{!! $item->user_price_currency.($item->user_price * $item->quantity) !!}</td>
                                <?php
                                $total = $total + ($item->admin_price * $item->quantity);
                                ?>
                            </tr>
                            <?php
                              $currency = $item->admin_price_currency;
                              $price = $item->admin_price;
                             ?>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="3">
                                <label>Total Amount:</label>
                                <span>{!! $currency . $total !!}</span>
                                <div class="table__action">
                                    @if($price != '')
                                      @if($value->status==2)
                                          <button class="button button--accent">Paid</button>
                                      @elseif($value->status==1)
                                          {!! Form::open(['route'=>['shopper.payment',$value->id]]) !!}
                                          <button class="button button--accent" type="submit">Pay Now</button>
                                          {!! Form::close() !!}
                                      @endif
                                    @else
                                      <button class="button button--accent">Request for admin approval</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
@stop

@section('contentFooter')
    <script>
        $(document).on('click', '.addNewItem', function (e) {
            e.preventDefault();

            str = '' +
                '<ul class="ul-reset">' +
                '    <li>' +
                '       <button class="btn btn-danger removeItem"><i class="material-icons">close</i></button>' +
                '    </li>' +
                '    <li class="gpf-input one-half-wrap">' +
                '        <div class="one-half-input">' +
                '            {!!Form::label('store_name','Store Name')!!}' +
                '            {!!Form::text('store_name[]', '', ['placeholder'=>'Store Name'])!!}' +
                '        </div>' +
                '        <div class="one-half-input">' +
                '            {!!Form::label('direct_link','Item Direct Link')!!}' +
                '            {!!Form::text('direct_link[]', '', ['placeholder'=>'Item Direct Link'])!!}' +
                '        </div>' +
                '    </li>' +
                '    <li class="gpf-input one-half-wrap">' +
                '        <div class="one-half-input">' +
                '            {!!Form::label('item_name','Item Name')!!}' +
                '            {!!Form::text('item_name[]', '', ['placeholder'=>'Item Name'])!!}' +
                '        </div>' +
                '        <div class="one-half-input">' +
                '            <ul class="ul-reset sub-field">' +
                '                <li>' +
                '                    {!!Form::label('user_price_currency','Price')!!}' +
                '                    <div class="sub__box">' +
                '                        <div class="sub__field">' +
                '                            {!!Form::select('user_price_currency[]',$currencies,'')!!}' +
                '                        </div>' +
                '                        <div class="sub__field">' +
                '                            {!!Form::text('user_price[]', '', ['placeholder'=>'Price'])!!}' +
                '                        </div>' +
                '                    </div>' +
                '                </li>' +
                '                <li>' +
                '                    {!!Form::label('color','Color')!!}' +
                '                    {!!Form::text('color[]', '', ['class'=>'form-control','placeholder'=>'Color'])!!}' +
                '                </li>' +
                '                <li>' +
                '                    {!!Form::label('quantity','Qty')!!}' +
                '                    {!!Form::text('quantity[]', '', ['class'=>'form-control','placeholder'=>'Qty'])!!}' +
                '                </li>' +
                '            </ul>' +
                '        </div>' +
                '    </li>' +
                '</ul>';

            $('.shopper-form').find('.shopper-action').before(str);
        });

        $(document).on('click', '.removeItem', function (e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        });
    </script>
@stop
