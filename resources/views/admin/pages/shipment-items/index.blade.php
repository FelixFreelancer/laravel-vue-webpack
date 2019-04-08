@extends('admin.layouts.master')

@section('contentHeader')

@stop

@section('page_title')
    Shipment ({!! $shipment->name !!}) Items
@stop

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="m-input-icon m-input-icon--left">
                                    <input type="text" class="form-control m-input m-input--solid"
                                           placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                        <a href="{!! url()->route('admin.shipment-items.create',$shipment->id) !!}"
                           class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                            <span><i class="m-nav__link-icon flaticon-add"></i><span>New item</span></span>
                        </a>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <table class="table m-table m-table--head-bg-metal  table-striped" id="dataTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Tracking Number</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
@stop

@section('contentFooter')
    <script>
        var shipment_id = "{!! $shipment->id !!}";
    </script>
    <script>
        $('#users_roles').selectpicker();

        var dTable = $('#dataTable').DataTable({
            ajax: {
                url: adminAjaxURL + 'shipments/' + shipment_id + '/datatable-shipment-items',
                data: function (d) {
                    d.search['value'] = $('#generalSearch').val();
                }
            },
            columns: [
                {data: 'id', name: 'id', className: 'dt-center', orderable: false, searchable: false},
                {data: 'item_name', name: 'item_name', className: 'dt-center'},
                {data: 'qty', name: 'qty', className: 'dt-center'},
                {data: 'amount', name: 'amount', className: 'dt-center'},
                {data: 'tracking_number', name: 'tracking_number', className: 'dt-center'},
                {data: 'action', searchable: false, orderable: false, className: 'dt-center'},
            ],
            order: [[0, 'desc']]
        });

        $('#generalSearch').on('keydown keyup blur change', $.debounce(500, function () {
            dTable.draw();
        }));

        $(document).on('click', '.deleteShipmentItem', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    dataType: 'json',
                    method: 'delete',
                    url: adminURL + "shipments/" + shipment_id + '/shipment-items/' + $(this).data('id'),
                    success: function (data) {
                        dTable.draw(false);
                    }
                });
            }
        });
    </script>
@stop
