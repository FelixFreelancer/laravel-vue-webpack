@extends('admin.layouts.master')

@section('contentHeader')

@stop

@section('page_title')
    Quotation
@stop

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="row">
                <div class="col-md-6">
                    <h3>User: {!! ucwords(strtolower($user->first_name.' '.$user->last_name)) !!}</h3>
                    <h3>Quotation Number: {!! $quotation_number !!}</h3>
                </div>
                <div class="col-md-6 text-right">
                    <h3>User Total: {!! $user_total !!}</h3>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            @if($status!='2')
                {!! Form::open(['route'=>['admin.quotations.update',$quotation_number],'class'=>'mt-5','method'=>'put']) !!}
            @endif
            <table class="table m-table m-table--head-bg-metal  table-striped">
                <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Store</th>
                    <th style="width: 10%;">Item</th>
                    <th style="width: 10%;">Link</th>
                    <th style="width: 10%;">Color</th>
                    <th style="width: 10%;">Quantity</th>
                    <th style="width: 10%;">User Price</th>
                    <th style="width: 10%;">Admin Price Currency</th>
                    <th style="width: 10%;">Admin Price</th>
                    <th style="width: 5%;">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quotation_items as $key=>$value)
                    <tr>
                        <td>{!! $value->id !!}</td>
                        <td>{!! $value->store_name !!}</td>
                        <td>{!! $value->item_name !!}</td>
                        <td><a href="{!! $value->direct_link !!}">Click here</a></td>
                        <td>{!! $value->color !!}</td>
                        <td>
                            {!! $value->quantity !!}
                            {!! Form::hidden('quantity['.$value->id.']',$value->quantity) !!}
                        </td>
                        <td>{!! $value->user_price_currency.$value->user_price !!}</td>
                        <td class="@if($errors->has('admin_price_currency.'.$value->id)) has-danger @endif">
                            @if(old('admin_price_currency'))
                                {!!  Form::select('admin_price_currency['.$value->id.']', $currencies, old('admin_price_currency.'.$value->id), ['class' => 'form-control adminPriceCurrency']) !!}
                            @else
                                {!!  Form::select('admin_price_currency['.$value->id.']', $currencies, $value->admin_price_currency, ['class' => 'form-control adminPriceCurrency']) !!}
                            @endif
                            @if($errors->has('admin_price_currency.'.$value->id))
                                <p class="help-block">{!! $errors->first('admin_price_currency.'.$value->id) !!}</p>
                            @endif
                        </td>
                        <td class="@if($errors->has('admin_price.'.$value->id)) has-danger @endif">
                            @if(old('admin_price'))
                                {!!  Form::number('admin_price['.$value->id.']', old('admin_price.'.$value->id), ['data-id'=>$value->id,'class' => 'form-control adminPrice']) !!}
                            @else
                                {!!  Form::number('admin_price['.$value->id.']', $value->admin_price, ['data-id'=>$value->id,'class' => 'form-control adminPrice']) !!}
                            @endif
                            @if($errors->has('admin_price.'.$value->id))
                                <p class="help-block">{!! $errors->first('admin_price.'.$value->id) !!}</p>
                            @endif
                        </td>
                        <td>
                            @if($value->status!=null)
                                {!! config('site.quotation_status.'.$value->status) !!}
                            @else
                                Pending
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="col-md-12 text-right mt-3">
                <h3>Admin Total: <span id="admin_total"></span></h3>
            </div>

            @if($status!='2')
                <div class="col-md-12 text-right mt-3">
                    <button class="btn btn-primary">Respond To Quote</button>
                </div>
            {!! Form::close() !!}
        @endif

        <!--end: Datatable -->
        </div>
    </div>
@stop

@section('contentFooter')
    <script>
        var quotation_number = "{!! $quotation_number !!}";
    </script>
    <script>
        $('#users_roles').selectpicker();

        function adminQuotationCalculate() {
            var total = 0;
            $('.adminPrice').each(function (key, item) {
                if ($(item).val() != '') {
                    total = total + (parseFloat($(item).val()) * parseFloat($('[name="quantity[' + $(item).data('id') + ']"]').val()));
                }
            });
            $('#admin_total').html($('.adminPriceCurrency').val() + total);
        }

        $(document).on('change blur keyup', '.adminPrice', function (e) {
            e.preventDefault();
            adminQuotationCalculate();
        });

        $(document).on('change', '.adminPriceCurrency', function (e) {
            e.preventDefault();
            $('.adminPriceCurrency').val($(this).val());
            adminQuotationCalculate();
        });

        $('#generalSearch').on('keydown keyup blur change', $.debounce(500, function () {
            dTable.draw();
        }));

        $('#users_roles').change($.debounce(500, function () {
            dTable.draw();
        }));


        $(document).on('click', '.deleteAdmin', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this admin?')) {
                $.ajax({
                    dataType: 'json',
                    method: 'delete',
                    url: adminURL + "admins/" + $(this).data('id'),
                    success: function (data) {
                        dTable.draw(false);
                    }
                });
            }
        });

        $(document).ready(function () {
            adminQuotationCalculate();
        });
    </script>
@stop
